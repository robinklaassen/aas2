<?php

namespace App\Http\Controllers;

use App\User;
use App\Member;
use App\Participant;
use App\Skill;
use App\Course;
use App\Event;
use App\EventPackage;
use App\Facades\Mollie;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemberRequest;
use App\Http\Requests\ParticipantRequest;
use App\Helpers\Payment\EventPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request as RequestFacade;
use App\Mail\internal\MemberOnEventNotification;
use App\Mail\internal\CoverageChangedNotification;
use App\Mail\internal\NewDeclaration;
use App\Mail\internal\ParticipantOnEventNotification;
use App\Mail\internal\ParticipantEditedEventCourseInformationNotification;
use Barryvdh\DomPDF\PDF;

class ProfileController extends Controller
{

	private $membersController;
	private $participantsController;

	public function __construct(MembersController $membersController, ParticipantsController $participantsController)
	{
		$this->membersController = $membersController;
		$this->participantsController = $participantsController;
	}

	# Helper function to determine controller to pass request to, based on authenticated user
	// TODO would love to type hint the output but union types are only supported from PHP 8.0 onward
	private function getController(Request $request)
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
		$successMessage = 'Je profiel is bewerkt!';
		$validatedRequest = ($request->user()->isMember()) ? app(MemberRequest::class) : app(ParticipantRequest::class);
		return $this->getController($request)->update($request->user()->profile, $validatedRequest, $successMessage);
	}

	# Upload photo
	public function upload(Request $request)
	{
		// TODO move all this code to a separate service
		if ($request->hasFile('photo')) {
			$file = $request->file('photo');
			if ($file->isValid()) {
				$profile = Auth::user()->profile;

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

	public function passwordSave(Request $request)
	{
		/** @var User */
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
	public function addCourse()
	{
		$member = Auth::user()->profile;
		$viewType = 'profile';
		return view('members.addCourse', compact('member', 'viewType'));
	}

	# Add course (member) (update database)
	public function addCourseSave()
	{
		$member = Auth::user()->profile;
		$course_id = RequestFacade::input('selected_course');  // TODO can probably be Request, as argument
		$course = Course::find($course_id);
		$courseLevelFrom = 0;

		if ($event_id = goesOnCamp($member)) {
			$camp = Event::findOrFail($event_id);
			$statusBefore = checkCoverage($camp, $course_id);
		}

		$status = $member->courses()->sync([$course_id], false);
		if ($status['attached'] == []) {
			$message = 'Vak reeds toegevoegd!';
		} else {
			// Check if member goes on camp in near future
			if ($event_id = goesOnCamp($member)) {
				$camp = \App\Event::findOrFail($event_id);
				// If so, check if this change makes or breaks the course coverage
				$courseLevelTo = RequestFacade::input('klas');  // TODO remove facade
				$member->courses()->updateExistingPivot($course_id, ['klas' => $courseLevelTo]);
				$statusAfter = checkCoverage($camp, $course_id);

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
				$member->courses()->updateExistingPivot($course_id, ['klas' => RequestFacade::input('klas')]);  // TODO remove facade
			}

			$message = 'Vak toegevoegd!';
		}
		return redirect('profile')->with([
			'flash_message' => $message
		]);
	}

	# Edit course (member) (form)
	public function editCourse($course_id)
	{
		$member = Auth::user()->profile;
		$course = $member->courses->find($course_id);
		$viewType = 'profile';
		return view('members.editCourse', compact('member', 'course', 'viewType'));
	}

	# Edit course (member) (update database)
	public function editCourseSave($course_id)
	{
		$member = Auth::user()->profile;
		$course = Course::find($course_id);
		$courseLevelFrom = $member->courses()->whereId($course_id)->first()->pivot->klas;

		// Check if member goes on camp in near future
		if ($event_id = goesOnCamp($member)) {
			$camp = Event::findOrFail($event_id);
			// If so, check if this change makes or breaks the course coverage
			$statusBefore = checkCoverage($camp, $course_id);
			$courseLevelTo = RequestFacade::input('klas');  // TODO remove facade
			$member->courses()->updateExistingPivot($course_id, ['klas' => $courseLevelTo]);
			$statusAfter = checkCoverage($camp, $course_id);

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
			$member->courses()->updateExistingPivot($course_id, ['klas' => RequestFacade::input('klas')]);  // TODO remove facade
		}

		return redirect('profile')->with([
			'flash_message' => 'Het vak is gewijzigd!'
		]);
	}

	# Remove (detach) a course from this member (form)
	public function removeCourseConfirm($course_id)
	{
		$member = Auth::user()->profile;
		$course = Course::findOrFail($course_id);
		$viewType = 'profile';
		return view('members.removeCourse', compact('member', 'course', 'viewType'));
	}

	# Remove (detach) a course from this member (update database)
	public function removeCourse($course_id)
	{
		$member = Auth::user()->profile;
		$course = Course::find($course_id);
		$courseLevelFrom = $member->courses()->whereId($course_id)->first()->pivot->klas;

		// Check if member goes on camp in near future
		if ($event_id = goesOnCamp($member)) {
			$camp = Event::findOrFail($event_id);
			// If so, check if this change makes or breaks the course coverage
			$statusBefore = checkCoverage($camp, $course_id);
			$courseLevelTo = 0;
			$member->courses()->detach($course_id);
			$statusAfter = checkCoverage($camp, $course_id);

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
	public function onCamp()
	{
		$profile = Auth::user()->profile;

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

		return view('profile.onCamp', compact('profile', 'camps', 'course_options', 'packages', 'package_type_per_camp'));
	}

	# Go on camp (update database)
	public function onCampSave(Request $request)
	{
		$camp = \App\Event::findOrFail($request->selected_camp);

		// Check if member or participant that's logged in
		if (Auth::user()->profile_type == 'App\Member') {
			$member = Auth::user()->profile;
			$status = $member->events()->sync([$request->selected_camp], false);
			if ($status['attached'] == []) {
				return redirect('profile')->with([
					'flash_message' => 'Je bent al op dit kamp!'
				]);
			} else {

				// Send update to camp committee
				Mail::send(new MemberOnEventNotification($member, $camp));

				// Send confirmation to member
				Mail::send(new \App\Mail\members\OnEventConfirmation($member, $camp));

				return redirect('profile')->with([
					'flash_message' => 'Je gaat op kamp!'
				]);
			}
		} elseif (Auth::user()->profile_type == Participant::class) {

			$package = \App\EventPackage::find($request->selected_package);

			// Check given package for camp
			if ($camp['package_type'] !== null && ($package === null || $package['type'] !== $camp['package_type'])) {
				return back()->with([
					'flash_error' => 'Er dient een pakket geselecteerd te worden bij voor dit kamp'
				]);
			} else if ($camp['package_type'] === null) {
				// remove any given package if the camp doesnt accept packages
				$package = null;
			}


			$participant = Auth::user()->profile;

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

				// Income table
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
				Mail::send(new \App\Mail\participants\OnEventConfirmation(
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
	public function editCamp($event_id)
	{
		// Redirect if not a participant
		if (Auth::user()->profile_type != 'App\Participant') {
			return redirect('profile');
		}

		$participant = Auth::user()->profile;
		$event = \App\Event::findOrFail($event_id);
		$course_options = \App\Course::orderBy('naam')->pluck('naam', 'id')->toArray();
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
		// Throw a hopeless error when user is not a participant ;)
		if (Auth::user()->profile_type == 'App\Participant') {
			$participant = Auth::user()->profile;
		}

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

		$camp = \App\Event::findOrFail($event_id);

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

	# Declaration (form)
	public function declareForm()
	{

		return view('profile.declare');
	}


	# Declaration (submit)
	public function declareSubmit(Request $request)
	{

		// Member profile
		$member = Auth::user()->profile;

		// Create destination folder if not exists
		$destination = public_path() . '/img/declarations/' . $member->id . '/';
		if (!file_exists($destination)) {
			mkdir($destination, 0777, true);
		}


		// Check and move files
		$file_numbers = [];
		$file_names = [];
		for ($i = 0; $i < count($request->get('denotion')); $i++) {
			if ($request->hasFile('uploaded' . ($i + 1))) {
				$file_numbers[] = $i + 1;
				$file = $request->file('uploaded' . ($i + 1));
				$filename = $file->getClientOriginalName();
				$newFilename = date('Y-m-d') . ' - ' . ($i + 1) . ' - ' . $filename;

				$file->move($destination, $newFilename);

				$file_names[] = $destination . $newFilename;
			}
		}

		// Obtain other inputs and check
		$fileNumberArray = $request->get('fileNumber');
		$dateArray = $request->get('date');
		$descriptionArray = $request->get('description');
		$amountArray = $request->get('amount');
		$giftArray = $request->get('gift'); // Watch it, not complete array, only has keys for checked
		$totalAmount = $request->get('totalAmount');

		$inputData = [];
		foreach ($fileNumberArray as $key => $fileNumber) {
			if ($fileNumber != "") {

				$gift = (isset($giftArray[$key])) ? 1 : 0;

				$inputData[] = [
					'fileNumber' => $fileNumber,
					'date' => $dateArray[$key],
					'description' => $descriptionArray[$key],
					'amount' => sprintf('%0.2f', $amountArray[$key]),
					'gift' => $gift
				];
			}
		}

		if ($inputData == []) {
			return redirect('profile')->with([
				'flash_error' => 'Geen informatie opgegeven, declaratie is niet verstuurd!'
			]);
		} else {

			// Create pdf of declaration info
			$formFilePath = $destination . date('Y-m-d') . ' declaratieformulier.pdf';
			$pdf = PDF::loadView('profile.declarePDF', compact('member', 'inputData', 'totalAmount'))
				->setPaper('a4')
				//->setOrientation('landscape')
				->setWarnings(true)
				->save($formFilePath);

			//return $pdf->stream();

			// Send email with data and files to treasurer and uploader
			Mail::send(
				new NewDeclaration(
					$member,
					$formFilePath,
					$inputData,
					$totalAmount,
					$file_names
				)
			);

			return redirect('profile')->with([
				'flash_message' => 'De declaratie is verstuurd!'
			]);
		}
	}

	# Show reviews of specified camp
	public function reviews($event_id)
	{
		$member = Auth::user()->profile;
		$event = Event::findOrFail($event_id);

		$options = [
			1 => 'Zeer slecht',
			2 => 'Slecht',
			3 => 'Gewoon',
			4 => 'Goed',
			5 => 'Zeer goed'
		];

		createReviewChart($event, 'stof', $options, $member);

		$options = [
			1 => 'Te weinig',
			2 => 'Weinig',
			3 => 'Voldoende',
			4 => 'Veel'
		];

		createReviewChart($event, 'aandacht', $options, $member);

		$options = [
			1 => 'Zeer vervelend',
			2 => 'Vervelend',
			3 => 'Gewoon',
			4 => 'Prettig',
			5 => 'Zeer prettig'
		];

		createReviewChart($event, 'mening', $options, $member);

		$options = [
			1 => 'Erg ontevreden',
			2 => 'Een beetje ontevreden',
			3 => 'Een beetje tevreden',
			4 => 'Erg tevreden'
		];

		createReviewChart($event, 'tevreden', $options, $member);

		return view('profile.reviews', compact('event', 'member'));
	}
}
