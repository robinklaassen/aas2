<?php

namespace App\Http\Controllers;

use App\Course;
use App\Event;
use App\EventPackage;
use App\Member;
use App\Facades\Mollie;
use App\Helpers\CourseCoverageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use App\Http\Requests\ParticipantRequest;
use App\Helpers\Payment\EventPayment;
use App\Services\Chart\ChartServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\internal\MemberOnEventNotification;
use App\Mail\internal\CoverageChangedNotification;
use App\Mail\internal\ParticipantOnEventNotification;
use App\Mail\internal\ParticipantEditedEventCourseInformationNotification;
use App\Mail\participants\OnEventConfirmation as ParticipantOnEventConfirmationMail;
use App\Mail\members\OnEventConfirmation as MemberOnEventConfirmationMail;

class ProfileController extends Controller
{

	private $membersController;
	private $participantsController;
	private $courseCoverageHelper;

	public function __construct(MembersController $membersController, ParticipantsController $participantsController, CourseCoverageHelper $courseCoverageHelper)
	{
		$this->membersController = $membersController;
		$this->participantsController = $participantsController;
		$this->courseCoverageHelper = $courseCoverageHelper;
	}

	# Helper function to determine controller to pass request to, based on authenticated user
	private function getController(Request $request): Controller
	{
		if ($request->user()->isMember()) {
			return $this->membersController;
		}

		if ($request->user()->isParticipant()) {
			return $this->participantsController;
		}
	}

	# Display requested resource.
	public function show(Request $request)
	{
		$viewType = 'profile';
		return $this->getController($request)->show($request->user()->profile, $viewType);
	}

	# Show form for editing requested resource.
	public function edit(Request $request)
	{
		$viewType = 'profile';
		return $this->getController($request)->edit($request->user()->profile, $viewType);
	}

	# Update the specified resource in storage.
	public function update(Request $request)
	{
		// Get the related form request from the container, this automatically performs the validation
		$validatedRequest = ($request->user()->isMember()) ? app(MemberRequest::class) : app(ParticipantRequest::class);
		$successMessage = 'Je profiel is bewerkt!';
		return $this->getController($request)->update($request->user()->profile, $validatedRequest, $successMessage);
	}

	# Upload photo
	public function upload(Request $request)
	{
		// TODO move all this code to a separate photo/avatar service
		if ($request->hasFile('photo')) {
			$file = $request->file('photo');
			if ($file->isValid()) {
				$profile = $request->user()->profile;

				// Move the file to storage
				$destPath = public_path() . '/img/profile/full/';
				$fName = $profile->id . '.' . $file->getClientOriginalExtension();
				$file->move($destPath, $fName);

				// Leave the cropping for now, just redirect to the profile page
				return redirect('profile')->with([
					'flash_message' => 'Je foto is geupload! Hard refresh je browser (ctrl + F5) om hem te kunnen zien'
				]);
			} else {
				// Upload error
				dd('upload error :(');
			}
		} else {
			// No file was selected
			return redirect()->back();
		}
	}

	# Set new password
	public function password(Request $request)
	{
		$user = $request->user();
		$viewType = 'profile';
		return view('users.password', compact('user', 'viewType'));
	}

	# Save new password in database
	public function passwordSave(Request $request)
	{
		$user = $request->user();
		$this->validate($request, [
			'password' => 'required|confirmed'
		]);

		$user->password = bcrypt($request->password);
		$user->save();

		return redirect('profile')->with([
			'flash_message' => 'Het nieuwe wachtwoord is ingesteld!'
		]);
	}

	# Add course (member) (form)
	public function addCourse(Request $request)
	{
		$member = $request->user()->profile;
		$viewType = 'profile';
		return view('members.addCourse', compact('member', 'viewType'));
	}

	# Add course (member) (update database)
	public function addCourseSave(Request $request)
	{
		$member = $request->user()->profile;
		$course_id = $request->input('selected_course');
		$course = Course::find($course_id);
		$courseLevelFrom = 0;

		$camp = $member->getNextCamp();

		if ($camp !== null) {
			$statusBefore = $this->courseCoverageHelper->getStatus($camp, $course);
		}

		$status = $member->courses()->sync([$course_id], false);
		if ($status['attached'] == []) {
			$message = 'Vak reeds toegevoegd!';
		} else {
			// TODO put below code into an event listener
			// Check if member goes on camp in near future
			if ($camp !== null) {
				// If so, check if this change makes or breaks the course coverage
				$courseLevelTo = $request->input('klas');
				$member->courses()->updateExistingPivot($course_id, ['klas' => $courseLevelTo]);
				$statusAfter = $this->courseCoverageHelper->getStatus($camp, $course);

				// If coverage status changes, send email to camp committe
				if ($statusBefore != $statusAfter) {
					Mail::send(
						new CoverageChangedNotification(
							$member,
							$camp,
							$course,
							$courseLevelFrom,
							$courseLevelTo,
							$statusAfter
						)
					);
				}
			} else {
				// If not, just update the course
				$member->courses()->updateExistingPivot($course_id, ['klas' => $request->input('klas')]);
			}

			$message = 'Vak toegevoegd!';
		}
		return redirect('profile')->with([
			'flash_message' => $message
		]);
	}

	# Edit course (member) (form)
	public function editCourse(Request $request, $course_id)
	{
		$member = $request->user()->profile;
		$course = $member->courses->find($course_id);
		$viewType = 'profile';
		return view('members.editCourse', compact('member', 'course', 'viewType'));
	}

	# Edit course (member) (update database)
	public function editCourseSave(Request $request, $course_id)
	{
		/** @var Member */
		$member = $request->user()->profile;
		$course = Course::find($course_id);
		$courseLevelFrom = $member->courses()->whereId($course_id)->first()->pivot->klas;

		// TODO put below code into event listener
		// Check if member goes on camp in near future
		$camp = $member->getNextFutureCamp();
		if ($camp !== null) {
			// If so, check if this change makes or breaks the course coverage
			$statusBefore = $this->courseCoverageHelper->getStatus($camp, $course);
			$courseLevelTo = $request->input('klas');
			$member->courses()->updateExistingPivot($course_id, ['klas' => $courseLevelTo]);
			$statusAfter = $this->courseCoverageHelper->getStatus($camp, $course);

			// If coverage status changes, send email to camp committe
			if ($statusBefore != $statusAfter) {
				Mail::send(
					new CoverageChangedNotification(
						$member,
						$camp,
						$course,
						$courseLevelFrom,
						$courseLevelTo,
						$statusAfter
					)
				);
			}
		} else {
			// If not, just update the course
			$member->courses()->updateExistingPivot($course_id, ['klas' => $request->input('klas')]);
		}

		return redirect('profile')->with([
			'flash_message' => 'Het vak is gewijzigd!'
		]);
	}

	# Remove (detach) a course from this member (form)
	public function removeCourseConfirm(Request $request, $course_id)
	{
		$member = $request->user()->profile;
		$course = Course::findOrFail($course_id);
		$viewType = 'profile';
		return view('members.removeCourse', compact('member', 'course', 'viewType'));
	}

	# Remove (detach) a course from this member (update database)
	public function removeCourse(Request $request, $course_id)
	{
		$member = $request->user()->profile;
		$course = Course::find($course_id);
		$courseLevelFrom = $member->courses()->whereId($course_id)->first()->pivot->klas;

		// TODO put below code into event listener
		// Check if member goes on camp in near future
		$camp = $member->getNextCamp();
		if ($camp !== null) {
			// If so, check if this change makes or breaks the course coverage
			$statusBefore = $this->courseCoverageHelper->getStatus($camp, $course);
			$courseLevelTo = 0;
			$member->courses()->detach($course_id);
			$statusAfter = $this->courseCoverageHelper->getStatus($camp, $course);

			// If coverage status changes, send email to camp committe
			if ($statusBefore != $statusAfter) {
				Mail::send(
					new CoverageChangedNotification(
						$member,
						$camp,
						$course,
						$courseLevelFrom,
						$courseLevelTo,
						$statusAfter
					)
				);
			}
		} else {
			// If not, just update the course
			$member->courses()->detach($course_id);
		}

		return redirect('profile')->with([
			'flash_message' => 'Het vak is verwijderd!'
		]);
	}

	# Go on camp (form)
	public function onCamp(Request $request)
	{
		$profile = $request->user()->profile;

		// List of future camps that are not full
		$camps = Event::participantEvent()
			->public()
			->open()
			->orderBy('datum_start', 'asc')
			->get();

		$package_type_per_camp = $camps->where('package_type', '!=', null)->mapWithKeys(function ($item) {
			return [$item['id'] => $item['package_type']];
		});

		$packages =  EventPackage::all()->groupBy('type');

		// List of courses
		$course_options = Course::orderBy('naam')->pluck('naam', 'id')->toArray();
		$course_options = [0 => '-geen vak-'] + $course_options;

		// TODO view should be separated to member and participant versions
		return view('profile.onCamp', compact('profile', 'camps', 'course_options', 'packages', 'package_type_per_camp'));
	}

	# Go on camp (update database)
	public function onCampSave(Request $request)
	{
		$camp = Event::findOrFail($request->selected_camp);

		// Check if member or participant that's logged in
		if ($request->user()->isMember()) {
			$member = $request->user()->profile;
			$status = $member->events()->sync([$request->selected_camp], false);
			if ($status['attached'] == []) {
				return redirect('profile')->with([
					'flash_message' => 'Je bent al op dit kamp!'
				]);
			} else {

				// Send update to camp committee
				Mail::send(new MemberOnEventNotification($member, $camp));

				// Send confirmation to member
				Mail::send(new MemberOnEventConfirmationMail($member, $camp));

				return redirect('profile')->with([
					'flash_message' => 'Je gaat op kamp!'
				]);
			}
		} elseif ($request->user()->isParticipant()) {

			$package = EventPackage::find($request->selected_package);

			// Check given package for camp
			if ($camp['package_type'] !== null && ($package === null || $package['type'] !== $camp['package_type'])) {
				return back()->with([
					'flash_error' => 'Er dient een pakket geselecteerd te worden bij voor dit kamp'
				]);
			} else if ($camp['package_type'] === null) {
				// remove any given package if the camp doesnt accept packages
				$package = null;
			}

			$participant = $request->user()->profile;

			$status = $participant->events()->sync([
				$request->selected_camp => ["package_id" => $package !== null ? $package->id : null]
			], false);
			if ($status['attached'] == []) {
				return redirect('profile')->with([
					'flash_message' => 'U heeft uw kind al voor dit kamp aangemeld!'
				]);
			} else {
				// Attach courses (with information)
				$givenCourses = [];
				foreach (array_unique($request->vak) as $key => $course_id) {
					if ($course_id != 0) {
						DB::table('course_event_participant')->insert(
							['course_id' => $course_id, 'event_id' => $request->selected_camp, 'participant_id' => $participant->id, 'info' => $request->vakinfo[$key]]
						);
						$givenCourses[] = ['naam' => \App\Course::find($course_id)->naam, 'info' => $request->vakinfo[$key]];
					}
				}

				// Setup payment
				$payment = (new EventPayment())
					->event($camp)
					->participant($participant)
					->package($package)
					->existing(true);
				$toPay = $payment->getTotalAmount();

				$iDeal = $request->iDeal;
				$type = "existing";

				// Send update to office committee
				Mail::send(new ParticipantOnEventNotification(
					$participant,
					$camp
				));

				// Send confirmation email to parent
				Mail::send(new ParticipantOnEventConfirmationMail(
					$participant,
					$camp,
					$givenCourses,
					$toPay,
					$iDeal,
					$type
				));

				// If they want to pay with iDeal, set up the payment now
				if ($iDeal == '1' && $toPay > 0) {
					return Mollie::process($payment);
				} else {
					// Return to profile
					return redirect('profile')->with([
						'flash_message' => 'Uw kind is aangemeld voor kamp!'
					]);
				}
			}
		}
	}

	# Edit camp (participant) (form)
	public function editCamp(Request $request, $event_id)
	{
		$participant = $request->user()->profile;
		$event = Event::findOrFail($event_id);
		$course_options = Course::orderBy('naam')->pluck('naam', 'id')->toArray();
		$course_options = [0 => '-geen vak-'] + $course_options;
		$result = DB::table('course_event_participant')->select('course_id', 'info')->whereParticipantIdAndEventId($participant->id, $event_id)->get();
		$retrieved_courses = [];
		foreach ($result as $row) {
			$retrieved_courses[] = ['id' => $row->course_id, 'info' => $row->info];
		}
		return view('profile.editCamp', compact('participant', 'event', 'course_options', 'retrieved_courses'));
	}

	# Edit camp (participant) (save)
	public function editCampSave(Request $request, $event_id)
	{
		$participant = $request->user()->profile;

		// Delete all current courses
		DB::table('course_event_participant')->whereParticipantIdAndEventId($participant->id, $event_id)->delete();

		// Insert new courses
		foreach (array_unique($request->vak) as $key => $course_id) {
			if ($course_id) {
				DB::table('course_event_participant')->insert(
					['course_id' => $course_id, 'event_id' => $event_id, 'participant_id' => $participant->id, 'info' => $request->vakinfo[$key]]
				);
			}
		}

		$camp = Event::findOrFail($event_id);

		// Send update to office committee
		Mail::send(
			new ParticipantEditedEventCourseInformationNotification(
				$participant,
				$camp
			)
		);

		return redirect('profile')->with([
			'flash_message' => 'De vakken voor dit kamp zijn bewerkt!'
		]);
	}

	# Show reviews of specified camp
	public function reviews(Request $request, $event_id, ChartServiceInterface $chartService)
	{
		$member = $request->user()->profile;
		$event = Event::findOrFail($event_id);

		$questions = collect([
			'stof',
			'aandacht',
			'mening',
			'tevreden'
		]);

		$questions->map(function ($question) use ($event, $member, $chartService) {
			$chartService->prepareEventReviewChart($event, $question, $member);
		});

		return view('profile.reviews', compact('event', 'member'));
	}
}
