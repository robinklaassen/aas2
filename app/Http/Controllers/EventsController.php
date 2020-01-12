<?php

namespace App\Http\Controllers;

use App\Event;
use App\Member;
use App\Participant;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Exports\EventNightRegisterReport;
use App\Exports\EventPaymentReport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class EventsController extends Controller
{

	public function __construct()
	{
		$this->authorizeResource(Event::class, 'event');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$events = Event::where('type', 'kamp')->get();
		$trainings = Event::where('type', 'training')->get();
		$others = Event::where('type', 'overig')->get();
		return view('events.index', compact('events', 'trainings', 'others'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$this->authorize("create");
		return view('events.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\EventRequest $request)
	{
		$this->authorize("create");
		Event::create($request->all());
		return redirect('events')->with([
			'flash_message' => 'Het evenement is aangemaakt!'
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Event $event)
	{
		// Redirect the viewer if the user profile is not attached to this event
		// $profile = \Auth::user()->profile;
		// if (!($profile->events->contains('id', $event->id)) && !(\Auth::user()->is_admin)) {
		// 	return redirect('profile');
		// }


		// Obtain participant course information
		$participantCourseString = array();
		foreach ($event->participants->all() as $p) {
			$result = \DB::table('course_event_participant')
				->where('event_id', $event->id)
				->where('participant_id', $p->id)
				->join('courses', 'course_event_participant.course_id', '=', 'courses.id')
				->orderBy('courses.naam')
				->get();

			$x = '';
			foreach ($result as $r) {
				$x .= $r->code . ' ';
			}

			$participantCourseString[$p->id] = substr($x, 0, strlen($x) - 1);
		}

		// Check which participants are 'new'
		$participantIsNew = [];
		foreach ($event->participants as $participant) {
			$num = $participant->events()->where('datum_eind', '<', $event->datum_start)->count();
			$participantIsNew[$participant->id] = ($num == 0) ? 1 : 0;
		}

		// Check number of participants to show
		if (\Auth::user()->is_admin) {
			$numberOfParticipants = $event->participants->count();
		} else {
			$numberOfParticipants = $event->participants()->wherePivot('geplaatst', 1)->count();
		}

		return view('events.show', compact('event', 'participantCourseString', 'participantIsNew', 'numberOfParticipants'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Event $event)
	{
		$this->authorize("update", $event);
		return view('events.edit', compact('event'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Event $event, Requests\EventRequest $request)
	{
		$this->authorize("update", $event);
		$event->update($request->all());
		return redirect('events/' . $event->id)->with([
			'flash_message' => 'Het evenement is bewerkt!'
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function delete(Event $event)
	{
		$this->authorize("delete", $event);
		return view('events.delete', compact('event'));
	}

	public function destroy(Event $event)
	{
		$this->authorize("delete", $event);
		$event->delete();
		return redirect('events')->with([
			'flash_message' => 'Het evenement is verwijderd!'
		]);
	}

	// Edit a member from this event
	public function editMember(Event $event, Member $member)
	{
		$this->authorize("editMember", [$event, $member]);
		$member = $event->members()->findOrFail($member->id);

		return view('events.editMember', compact('event', 'member'));
	}

	public function editMemberSave(Event $event, Member $member, Request $request)
	{
		$this->authorize("editMember", [$event, $member]);
		$event->members()->updateExistingPivot($member, ['wissel' => $request->wissel, 'wissel_datum_start' => $request->wissel_datum_start, 'wissel_datum_eind' => $request->wissel_datum_eind]);


		return redirect('events/' . $event->id)->with([
			'flash_message' => 'De leiding op dit evenement is bewerkt!'
		]);
	}

	// Remove (detach) a member from this event
	public function removeMemberConfirm(Event $event, Member $member)
	{
		$this->authorize("removeMember", [$event, $member]);
		return view('events.removeMember', compact('event', 'member'));
	}

	public function removeMember(Event $event, Member $member)
	{
		$this->authorize("removeMember", [$event, $member]);

		$event->members()->detach($member->id);
		return redirect('events/' . $event->id)->with([
			'flash_message' => 'Het lid is van dit evenement verwijderd!'
		]);
	}

	// Edit a participant from this event (date of payment and course info)
	public function editParticipant(Event $event, Participant $participant)
	{
		$this->authorize("editParticipant", [$event, $participant]);

		$participant = $event->participants->find($participant->id);

		$result = \DB::table('course_event_participant')->select('course_id', 'info')->whereParticipantIdAndEventId($participant->id, $event->id)->get();
		$retrieved_courses = [];
		foreach ($result as $row) {
			$retrieved_courses[] = ['id' => $row->course_id, 'info' => $row->info];
		}

		return view('events.editParticipant', compact('event', 'participant', 'retrieved_courses'));
	}

	public function editParticipantSave(Event $event, Participant $participant, Request $request)
	{
		$this->authorize("editParticipant", [$event, $participant]);

		// Update datum_betaling and geplaatst in pivot table
		$event->participants()->updateExistingPivot($participant->id, ['datum_betaling' => $request->datum_betaling, 'geplaatst' => $request->geplaatst]);

		// Delete all current courses
		\DB::table('course_event_participant')->whereParticipantIdAndEventId($participant->id, $event->id)->delete();

		// Insert new courses
		foreach (array_unique($request->vak) as $key => $course_id) {
			if ($course_id) {
				\DB::table('course_event_participant')->insert(
					['course_id' => $course_id, 'event_id' => $event->id, 'participant_id' => $participant->id, 'info' => $request->vakinfo[$key]]
				);
			}
		}

		return redirect('events/' . $event->id)->with([
			'flash_message' => 'De deelnemer op dit evenement is bewerkt!'
		]);
	}

	// Remove (detach) a participant from this event
	public function removeParticipantConfirm(Event $event, Participant $participant)
	{
		$this->authorize("removeParticipant", [$event, $participant]);
		return view('events.removeParticipant', compact('event', 'participant'));
	}

	public function removeParticipant(Event $event, Participant $participant)
	{
		$this->authorize("removeParticipant", [$event, $participant]);

		$event->participants()->detach($participant->id);
		\DB::table('course_event_participant')->where('event_id', $event->id)->where('participant_id', $participant->id)->delete();
		return redirect('events/' . $event->id)->with([
			'flash_message' => 'De deelnemer is van dit evenement verwijderd!'
		]);
	}

	# Export participant info for this camp
	public function export(Event $event)
	{
		$this->authorize("exportParticipants", $event);

		// Redirect if not camp
		if ($event->type != 'kamp') {
			return redirect('events');
		}

		$participantCourses = array();
		// Get participants
		$participants = $event->participants()->orderBy('voornaam')->get();
		$num_participants_placed = $event->participants()->wherePivot('geplaatst', 1)->count();

		foreach ($participants as $row) {
			$participantCourses[$row->id] = array();
		}

		// Construct course array
		$result = \DB::table('course_event_participant')
			->where('event_id', '=', $event->id)
			->join('courses', 'course_event_participant.course_id', '=', 'courses.id')
			->orderBy('courses.naam')
			->get();

		foreach ($result as $row) {
			$participantCourses[$row->participant_id][] = ['naam' => $row->naam, 'info' => $row->info];
		}

		// Some more or less useful statistics
		$stats['num_males'] = $event->participants()->where('geslacht', 'M')->count();
		$stats['num_females'] = $event->participants()->where('geslacht', 'V')->count();
		$stats['num_VMBO'] = $event->participants()->where('niveau', 'VMBO')->count();
		$stats['num_HAVO'] = $event->participants()->where('niveau', 'HAVO')->count();
		$stats['num_VWO'] = $event->participants()->where('niveau', 'VWO')->count();

		// And age distribution and if new or not
		$stats['num_new'] = 0;
		$stats['num_old'] = 0;
		$ages = array();
		foreach ($participants as $participant) {
			$ages[] = $participant->geboortedatum->diffInYears($event->datum_start);

			$num = $participant->events()->where('datum_eind', '<', $event->datum_start)->count();
			($num == 0) ? $stats['num_new']++ : $stats['num_old']++;
		}
		$age_freq = array_count_values($ages);
		ksort($age_freq, SORT_NUMERIC);

		// And number of final year students (exam candidates)
		$stats['num_exam'] = 0;
		foreach ($participants as $p) {
			if (in_array($p->klas . $p->niveau, ['4VMBO', '5HAVO', '6VWO'])) {
				$stats['num_exam']++;
			}
		}

		// Generate and output PDF
		$pdf = \PDF::loadView('events.export', compact('event', 'participants', 'num_participants_placed', 'participantCourses', 'stats', 'age_freq'))->setPaper('a4')->setWarnings(true);
		return $pdf->stream();
	}

	# Check course coverage (vakdekking)
	public function check(Event $event, $type)
	{
		$this->authorize("subjectCheck", $event);

		// Redirect if not camp
		if ($event->type != 'kamp') {
			return redirect('events');
		}

		$courses = \App\Course::orderBy('naam')->get();
		$memberIDs = $event->members->pluck('id')->toArray();

		// Loop through all courses
		foreach ($courses as $course) {
			// Obtain members that have this course
			$result = \DB::table('course_member')
				->whereIn('member_id', $memberIDs)
				->where('course_id', $course->id)
				->join('members', 'course_member.member_id', '=', 'members.id')
				->select('members.voornaam', 'course_member.klas')
				->orderBy('members.voornaam')
				->get();

			$numbers[$course->id]['m'] = count($result);
			$tooltips[$course->id]['m'] = '';
			$levels['m'] = [];
			foreach ($result as $row) {
				$tooltips[$course->id]['m'] .= $row->voornaam . ' (' . $row->klas . ')<br/>';
				$levels['m'][] = $row->klas;
			}


			// Obtain participants that have this course
			$result = \DB::table('course_event_participant')
				->where('event_id', $event->id)
				->where('course_id', $course->id)
				->join('participants', 'course_event_participant.participant_id', '=', 'participants.id')
				->select('participants.id', 'participants.voornaam', 'participants.klas')
				->orderBy('participants.voornaam')
				->get();

			// Filter out unplaced participants, if requested
			if ($type == 'placed') {
				$unplaced = $event->participants()->where('geplaatst', 0)->get()->pluck('id');
				$result = $result->filter(function ($participant) use ($unplaced) {
					return !$unplaced->contains($participant->id);
				});
			}

			$numbers[$course->id]['p'] = count($result);
			$tooltips[$course->id]['p'] = '';
			$levels['p'] = [];
			foreach ($result as $row) {
				$tooltips[$course->id]['p'] .= $row->voornaam . ' (' . $row->klas . ')<br/>';
				$levels['p'][] = $row->klas;
			}

			// Now determine the status of this course...
			$status[$course->id] = 'ok';

			// Start by checking if just the number of members is sufficient
			if (3 * $numbers[$course->id]['m'] < $numbers[$course->id]['p']) {
				$status[$course->id] = 'badquota';
			} else {
				// If the number of members is sufficient, then check if their levels are

				// Create 'triple-array' for member levels
				$m = [];
				foreach ($levels['m'] as $val) {
					$m[] = $val;
					$m[] = $val;
					$m[] = $val;
				}

				$p = $levels['p'];

				// Sort both arrays from high to low
				rsort($m, SORT_NUMERIC);
				rsort($p, SORT_NUMERIC);

				// Compare element-wise
				foreach ($p as $key => $value) {
					if ($value > $m[$key]) {
						$status[$course->id] = 'badlevel';
					}
				}
			}
		}

		return view('events.check', compact('type', 'event', 'courses', 'numbers', 'tooltips', 'status'));
	}

	# Calculate camp budget
	public function budget(Event $event)
	{
		$this->authorize("budget", $event);

		$data = new \StdClass();

		// Budget per person per day
		$data->pppd = 5;

		// num_m is only full members
		$data->num_m = 0;
		$wbd = 0;
		$wissel = [];
		foreach ($event->members()->get() as $member) {
			if ($member->pivot->wissel == false) {
				$data->num_m++;
			} else {
				$s = \DateTime::createFromFormat('Y-m-d', $member->pivot->wissel_datum_start);
				$e = \DateTime::createFromFormat('Y-m-d', $member->pivot->wissel_datum_eind);

				$days = $s->diff($e)->days + 1;

				$wissel[] = [
					'name' => $member->voornaam,
					'days' => $days
				];

				$wbd += ($days * $data->pppd);
			}
		}

		$data->num_p = $event->participants()->count();

		$data->days_m = $event->datum_eind->diffInDays($event->datum_voordag);
		$data->days_p = $event->datum_eind->diffInDays($event->datum_start);

		$data->total = ((($data->num_m * $data->days_m) + ($data->num_p * $data->days_p)) * $data->pppd) + $wbd;

		return view('events.budget', compact('event', 'data', 'wissel'));
	}

	# Output email addresses as plain text list
	public function email(Event $event)
	{
		$this->authorize("mailing", $event);

		$email['members'] = "";
		$num['members'] = 0;
		$email['kids'] = "";
		$num['kids'] = 0;
		$email['parents'] = "";
		$num['parents'] = 0;

		foreach ($event->members()->orderBy('voornaam')->get() as $member) {
			if ($member->email_anderwijs) {
				$email['members'] .= $member->email_anderwijs . ', ';
				$num['members']++;
			}
		}

		foreach ($event->participants()->orderBy('voornaam')->get() as $participant) {
			if ($participant->email_deelnemer) {
				$email['kids'] .= $participant->email_deelnemer . ', ';
				$num['kids']++;
			}

			if ($participant->email_ouder) {
				$email['parents'] .= $participant->email_ouder . ', ';
				$num['parents']++;
			}
		}

		$email['members'] = rtrim($email['members'], ', ');
		$email['kids'] = rtrim($email['kids'], ', ');
		$email['parents'] = rtrim($email['parents'], ', ');

		return view('events.email', compact('event', 'email', 'num'));
	}

	# Send all participants on camp (confirm)
	public function sendConfirm(Event $event)
	{
		$this->authorize("placement", $event);
		return view('events.sendAll', compact('event'));
	}

	# Send all participants on camp (execute)
	public function send(Event $event)
	{
		$this->authorize("placement", $event);

		$ids = $event->participants()->pluck('id')->toArray();

		foreach ($ids as $id) {
			$event->participants()->updateExistingPivot($id, ['geplaatst' => 1]);
		}

		return redirect('events/' . $event->id)->with([
			'flash_message' => 'Alle deelnemers zijn geplaatst!'
		]);
	}

	# Generate payments overview
	public function payments(Event $event)
	{
		$this->authorize("paymentoverview", $event);
		return Excel::download(new EventPaymentReport($event), date('Y-m-d') . ' Betalingsoverzicht ' . $event->code . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
	}

	# Generate night register
	public function nightRegister(Event $event)
	{
		$this->authorize("nightRegister", $event);
		return Excel::download(new EventNightRegisterReport($event), date('Y-m-d') . ' Nachtregister ' . $event->location->plaats . ' ' . $event->code . '.xlsx', \Maatwebsite\Excel\Excel::XLSX);
	}

	# Generate iCal stream of events
	public function iCalendar()
	{

		$events = Event::orderBy('datum_start', 'asc')->where('openbaar', 1)->get();
		$members = Member::whereIn('soort', ['normaal', 'aspirant'])->where('publish_birthday', 1)->get();

		$response = response()->view('events.ical', compact('events', 'members'));

		if (env('APP_ENV') == 'production') {
			$response
				->header('Content-Type', 'text/calendar; charset=utf-8')
				->header('Content-Disposition', 'inline; filename=anderwijs.ics');
		} else {
			$response->header('Content-Type', 'text/plain; charset=utf-8');
		}

		return $response;
	}

	# Join members to event (form)
	public function joinMembers(Event $event)
	{
		$this->authorize("addMember", $event);
		$members = Member::where('soort', '<>', 'oud')->orderBy('voornaam')->get();

		return view('events.join-members', compact('event', 'members'));
	}

	# Join members to event (save)
	public function joinMembersSave(Event $event, Request $request)
	{
		$this->authorize("addMember", $event);
		foreach ($request->members as $member_id) {
			$event->members()->attach($member_id);
		}

		return redirect('events/' . $event->id)->with([
			'flash_message' => 'De leden zijn gekoppeld!'
		]);
	}

	# Show review results
	public function reviews(Event $event)
	{

		$this->authorize("viewReviewResults", $event);
		// // Auth: either have to be admin or have gone on this camp as a member
		// if (!(\Auth::user()->is_admin)) {

		// 	$is_member = (\Auth::user()->profile_type == "App\Member");
		// 	$on_camp = \Auth::user()->profile->events->contains("id", $event->id);

		// 	if (!($is_member && $on_camp)) {
		// 		return redirect()->back();
		// 	}
		// }

		// Repeatedly set options and create charts using a helper function based on LavaCharts

		$options = [
			1 => 'Te weinig',
			2 => 'Voldoende',
			3 => 'Te veel'
		];

		createReviewChart($event, 'bs-mening', $options);

		$options = [
			1 => 'Erg ontevreden',
			2 => 'Een beetje ontevreden',
			3 => 'Een beetje tevreden',
			4 => 'Erg tevreden'
		];

		createReviewChart($event, 'bs-tevreden', $options);

		$options = [
			0 => 'Nee',
			1 => 'Ja'
		];

		createReviewChart($event, 'bs-manier', $options);

		createReviewChart($event, 'bs-thema', $options);

		$options = [
			1 => 'Veel te weinig',
			2 => 'Weinig',
			3 => 'Genoeg',
			4 => 'Meer dan genoeg'
		];

		createReviewChart($event, 'slaaptijd', $options);

		$options = [
			1 => 'Veel te kort',
			2 => 'Te kort',
			3 => 'Precies goed',
			4 => 'Te lang',
			5 => 'Veel te lang'
		];

		createReviewChart($event, 'kamplengte', $options);

		return view('events.reviews', compact('event'));
	}

	# Move participant to different camp (show form)
	public function moveParticipant(Event $event, $participant_id)
	{
		$participant = $event->participants->find($participant_id);
		$events = Event::where('type', 'kamp')->orderBy('datum_start', 'desc')->get();
		return view('events.move-participant', compact('event', 'participant', 'events'));
	}

	# Move participant to different camp (process)
	public function moveParticipantSave(Event $event, $participant_id, Request $request)
	{
		# $event->id is the id of the current event
		# $request->destination is the id of the destination event

		foreach (['event_participant', 'course_event_participant'] as $table) {
			$query = "UPDATE " . $table . " SET event_id = " . $request->destination . " WHERE event_id = " . $event->id . " AND participant_id = " . $participant_id;

			\DB::statement($query);
		}

		return redirect('events')->with([
			'flash_message' => 'De deelnemer is verplaatst!'
		]);
	}
}
