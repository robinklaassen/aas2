<?php namespace App\Http\Controllers;

use App\Event;
use App\Member;
use App\Participant;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Khill\Lavacharts\Lavacharts;

use Illuminate\Http\Request;

class EventsController extends Controller {

	public function __construct()
	{
		// You need to be logged in and have admin rights to access
		$this->middleware('auth', ['except' => ['iCalendar']]);
		$this->middleware('admin', ['except' => ['show', 'iCalendar', 'reviews']]);
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$camps = Event::where('type','kamp')->get();
		$trainings = Event::where('type','training')->get();
		$others = Event::where('type','overig')->get();
		return view('events.index', compact('camps','trainings','others'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('events.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\EventRequest $request)
	{
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
		$profile = \Auth::user()->profile;
		if (!in_array($event->id, $profile->events->pluck('id')->toArray()) && !(\Auth::user()->is_admin))
		{
			return redirect('profile');
		}
		
		// Obtain participant course information
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
			
			$participantCourseString[$p->id] = substr($x,0,strlen($x)-1);
		}
		
		// Check which participants are 'new'
		$participantIsNew = [];
		foreach ($event->participants as $participant) {
			$num = $participant->events()->where('datum_eind', '<', $event->datum_start)->count();
			$participantIsNew[$participant->id] = ($num == 0) ? 1 : 0;
		}
		
		// Check if all information should be shown (not to unplaced participant profile)
		$showAll = true;
		if (\Auth::user()->profile_type == "App\Participant" && !($profile->events()->find($event->id)->pivot->geplaatst) ) {
			$showAll = false;
			
		}
		
		// Check number of participants to show
		if (\Auth::user()->is_admin) {
			$numberOfParticipants = $event->participants->count();
		} else {
			$numberOfParticipants = $event->participants()->wherePivot('geplaatst',1)->count();
		}

		return view('events.show', compact('event', 'participantCourseString', 'participantIsNew', 'showAll', 'numberOfParticipants'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Event $event)
	{
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
		$event->update($request->all());
		return redirect('events/'.$event->id)->with([
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
		return view('events.delete', compact('event'));
	}
	
	public function destroy(Event $event)
	{
		$event->delete();
		return redirect('events')->with([
			'flash_message' => 'Het evenement is verwijderd!'
		]);
	}
	
	// Edit a member from this event
	public function editMember(Event $event, $member_id)
	{
		$member = $event->members->find($member_id);
		return view('events.editMember', compact('event', 'member'));
	}
	
	public function editMemberSave(Event $event, $member_id, Request $request)
	{
		$event->members()->updateExistingPivot($member_id, ['wissel' => $request->wissel, 'wissel_datum_start' => $request->wissel_datum_start, 'wissel_datum_eind' => $request->wissel_datum_eind]);
		
		return redirect('events/'.$event->id)->with([
			'flash_message' => 'De leiding op dit evenement is bewerkt!'
		]);
	}
	
	// Remove (detach) a member from this event
	public function removeMemberConfirm(Event $event, $member_id)
	{
		$member = \App\Member::findOrFail($member_id);
		return view('events.removeMember', compact('event', 'member'));
	}
	
	public function removeMember(Event $event, $member_id)
	{
		$event->members()->detach($member_id);
		return redirect('events/'.$event->id)->with([
			'flash_message' => 'Het lid is van dit evenement verwijderd!'
		]);
	}
	
	// Edit a participant from this event (date of payment and course info)
	public function editParticipant(Event $event, $participant_id)
	{
		$participant = $event->participants->find($participant_id);
		
		$result = \DB::table('course_event_participant')->select('course_id','info')->whereParticipantIdAndEventId($participant_id, $event->id)->get();
		$retrieved_courses = [];
		foreach ($result as $row)
		{
			$retrieved_courses[] = ['id' => $row->course_id, 'info' => $row->info];
		}
		
		return view('events.editParticipant', compact('event', 'participant', 'retrieved_courses'));
	}
	
	public function editParticipantSave(Event $event, $participant_id, Request $request)
	{
		// Update datum_betaling and geplaatst in pivot table
		$event->participants()->updateExistingPivot($participant_id, ['datum_betaling' => $request->datum_betaling, 'geplaatst' => $request->geplaatst]);
		
		// Delete all current courses
		\DB::table('course_event_participant')->whereParticipantIdAndEventId($participant_id, $event->id)->delete();
		
		// Insert new courses
		foreach (array_unique($request->vak) as $key => $course_id)
		{
			if ($course_id)
			{
				\DB::table('course_event_participant')->insert(
					['course_id' => $course_id, 'event_id' => $event->id, 'participant_id' => $participant_id, 'info' => $request->vakinfo[$key]]
				);
			}
		}
		
		return redirect('events/'.$event->id)->with([
			'flash_message' => 'De deelnemer op dit evenement is bewerkt!'
		]);
	}
	
	// Remove (detach) a participant from this event
	public function removeParticipantConfirm(Event $event, $participant_id)
	{
		$participant = \App\Participant::findOrFail($participant_id);
		return view('events.removeParticipant', compact('event', 'participant'));
	}
	
	public function removeParticipant(Event $event, $participant_id)
	{
		$event->participants()->detach($participant_id);
		\DB::table('course_event_participant')->where('event_id', $event->id)->where('participant_id', $participant_id)->delete();
		return redirect('events/'.$event->id)->with([
			'flash_message' => 'De deelnemer is van dit evenement verwijderd!'
		]);
	}
	
	# Export participant info for this camp
	public function export(Event $camp)
	{
		// Redirect if not camp
		if ($camp->type != 'kamp')
		{
			return redirect('events');
		}
		
		// Get participants
		$participants = $camp->participants()->orderBy('voornaam')->get();
		$num_participants_placed = $camp->participants()->wherePivot('geplaatst', 1)->count();
		
		// Construct course array
		$result = \DB::table('course_event_participant')
			->where('event_id', '=', $camp->id)
			->join('courses', 'course_event_participant.course_id', '=', 'courses.id')
			->orderBy('courses.naam')
			->get();
		foreach ($result as $row)
		{
			$participantCourses[$row->participant_id][] = ['naam' => $row->naam, 'info' => $row->info];
		}

		// Some more or less useful statistics
		$stats['num_males'] = $camp->participants()->where('geslacht','M')->count();
		$stats['num_females'] = $camp->participants()->where('geslacht','V')->count();
		$stats['num_VMBO'] = $camp->participants()->where('niveau','VMBO')->count();
		$stats['num_HAVO'] = $camp->participants()->where('niveau','HAVO')->count();
		$stats['num_VWO'] = $camp->participants()->where('niveau','VWO')->count();
		
		// And age distribution and if new or not
		$stats['num_new'] = 0; $stats['num_old'] = 0;
		foreach ($participants as $participant)
		{
			$ages[] = $participant->geboortedatum->diffInYears($camp->datum_start);
			
			$num = $participant->events()->where('datum_eind', '<', $camp->datum_start)->count();
			($num == 0) ? $stats['num_new']++ : $stats['num_old']++;
		}
		$age_freq = array_count_values($ages);
		ksort($age_freq, SORT_NUMERIC);
		
		// And number of final year students (exam candidates)
		$stats['num_exam'] = 0;
		foreach ($participants as $p) {
			if(in_array($p->klas . $p->niveau, ['4VMBO', '5HAVO', '6VWO'])) {
				$stats['num_exam']++;
			}
		}
		
		// Generate and output PDF
		$pdf = \PDF::loadView('events.export', compact('camp', 'participants', 'num_participants_placed', 'participantCourses', 'stats', 'age_freq'))->setPaper('a4')->setWarnings(true);
		return $pdf->stream();
	}
	
	# Check course coverage (vakdekking)
	public function check(Event $camp, $type)
	{
		// Redirect if not camp
		if ($camp->type != 'kamp')
		{
			return redirect('events');
		}
		
		$courses = \App\Course::orderBy('naam')->get();
		$memberIDs = $camp->members->pluck('id')->toArray();
		
		// Loop through all courses
		foreach ($courses as $course)
		{
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
			foreach ($result as $row)
			{
				$tooltips[$course->id]['m'] .= $row->voornaam . ' (' . $row->klas . ')<br/>';
				$levels['m'][] = $row->klas;
			}
			
			
			// Obtain participants that have this course
			$result = \DB::table('course_event_participant')
				->where('event_id', $camp->id)
				->where('course_id', $course->id)
				->join('participants', 'course_event_participant.participant_id', '=', 'participants.id')
				->select('participants.id', 'participants.voornaam', 'participants.klas')
				->orderBy('participants.voornaam')
				->get();

			// Filter out unplaced participants, if requested
			if ($type == 'placed')
			{
				$unplaced = $camp->participants()->where('geplaatst',0)->pluck('id')->toArray();
				$result = array_where($result, function($key, $value) use ($unplaced) {
					return !in_array($value->id, $unplaced);
				});
			}
			
			$numbers[$course->id]['p'] = count($result);
			$tooltips[$course->id]['p'] = '';
			$levels['p'] = [];
			foreach ($result as $row)
			{
				$tooltips[$course->id]['p'] .= $row->voornaam . ' (' . $row->klas . ')<br/>';
				$levels['p'][] = $row->klas;
			}
			
			// Now determine the status of this course...
			$status[$course->id] = 'ok';
			
			// Start by checking if just the number of members is sufficient
			if (3 * $numbers[$course->id]['m'] < $numbers[$course->id]['p'])
			{
				$status[$course->id] = 'badquota';
			}
			else
			{
				// If the number of members is sufficient, then check if their levels are
				
				// Create 'triple-array' for member levels
				$m = [];
				foreach ($levels['m'] as $val)
				{
					$m[] = $val;
					$m[] = $val;
					$m[] = $val;
				}

				$p = $levels['p'];

				// Sort both arrays from high to low
				rsort($m, SORT_NUMERIC);
				rsort($p, SORT_NUMERIC);
				
				// Compare element-wise
				foreach ($p as $key => $value)
				{
					if ($value > $m[$key])
					{
						$status[$course->id] = 'badlevel';
					}
				}
			}	
		}
		
		return view('events.check', compact('type', 'camp', 'courses', 'numbers', 'tooltips', 'status'));
	}
	
	# Calculate camp budget
	public function budget(Event $camp) {
		
		$data = new \StdClass();
		
		// Budget per person per day
		$data->pppd = 5;
		
		// num_m is only full members
		$data->num_m = 0;
		$wbd = 0;
		$wissel = [];
		foreach ($camp->members()->get() as $member) {
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
		
		$data->num_p = $camp->participants()->count();
		
		$data->days_m = $camp->datum_eind->diffInDays($camp->datum_voordag);
		$data->days_p = $camp->datum_eind->diffInDays($camp->datum_start);
		
		$data->total = ((($data->num_m * $data->days_m) + ($data->num_p * $data->days_p)) * $data->pppd) + $wbd;
		
		return view('events.budget', compact('camp', 'data', 'wissel'));
		
	}
	
	# Output email addresses as plain text list
	public function email(Event $camp) {
		
		$email['members'] = ""; $num['members'] = 0;
		$email['kids'] = ""; $num['kids'] = 0;
		$email['parents'] = ""; $num['parents'] = 0;
		
		foreach ($camp->members()->orderBy('voornaam')->get() as $member) {
			if ($member->email_anderwijs) {
				$email['members'] .= $member->email_anderwijs . ', ';
				$num['members']++;
			}
		}
		
		foreach ($camp->participants()->orderBy('voornaam')->get() as $participant) {
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
		
		return view('events.email', compact('camp', 'email', 'num'));
	}
	
	# Send all participants on camp (confirm)
	public function sendConfirm(Event $event) {
		return view('events.sendAll', compact('event'));
	}
	
	# Send all participants on camp (execute)
	public function send(Event $event) {
		
		$ids = $event->participants()->pluck('id')->toArray();
		
		foreach ($ids as $id) {
			$event->participants()->updateExistingPivot($id, ['geplaatst' => 1]);
		}
		
		return redirect('events/'.$event->id)->with([
			'flash_message' => 'Alle deelnemers zijn geplaatst!'
		]);
	}
	
	# Generate payments overview
	public function payments(Event $event) {
		// Fill data array
		$data = [];
		foreach ($event->participants()->orderBy('voornaam')->get() as $p) {
			
			// Payment amount
			switch ($p->inkomen)
			{
				case 0:
					$toPay = $event->prijs;
					break;
				
				case 1:
					$toPay = round((0.85 * $event->prijs)/5) * 5;
					break;
					
				case 2:
					$toPay = round((0.7 * $event->prijs)/5) * 5;
					break;
					
				case 3:
					$toPay = round((0.5 * $event->prijs)/5) * 5;
					break;
			}
			
			// Date of payment is not Carbon :(
			$x = $p->pivot->datum_betaling;
			$betaling = substr($x,8,2) . '-' . substr($x,5,2) . '-' . substr($x,0,4);
			
			// Declaration of income only when asked for
			//dd($p->inkomensverklaring->year);
			$i = ($p->inkomensverklaring->year > 0) ? $p->inkomensverklaring->format('d-m-Y') : null;
			if ($p->inkomen == 0) {$i = 'x';}
			
			$data[] = [
				'Naam' => str_replace('  ',' ',$p->voornaam . ' ' . $p->tussenvoegsel . ' ' . $p->achternaam),
				'Bedrag' => $toPay,
				'Inschrijving' => $p->pivot->created_at->format('d-m-Y'),
				'Betaling' => $betaling,
				'Inkomensverklaring' => $i,
				'Correct' => null,
				'Opmerking' => null
			];
		} 

		// Create and export Excel sheet from data
		\Excel::create(date('Y-m-d') . ' Betalingsoverzicht ' . $event->code, function($excel) use($data) {
			$excel->sheet('Overzicht', function($sheet) use($data) {
				$sheet->fromArray($data);
			});
		})->export('xlsx');
		
	}
	
	# Generate night register
	public function nightRegister(Event $event) {
		// Fill data array for participants
		$pdata = [];
		foreach ($event->participants()->orderBy('voornaam')->get() as $p) {
			$pdata[] = [
				'Voornaam' => $p->voornaam,
				'Tussenvoegsel' => $p->tussenvoegsel,
				'Achternaam' => $p->achternaam,
				'Geboortedatum' => $p->geboortedatum->format('d-m-Y'),
				'Adres' => $p->adres,
				'Postcode' => $p->postcode,
				'Plaats' => $p->plaats
			];
		}
		
		// Fill data array for members
		$mdata = [];
		foreach ($event->members()->orderBy('voornaam')->get() as $m) {
			$mdata[] = [
				'Voornaam' => $m->voornaam,
				'Tussenvoegsel' => $m->tussenvoegsel,
				'Achternaam' => $m->achternaam,
				'Geboortedatum' => $m->geboortedatum->format('d-m-Y'),
				'Adres' => $m->adres,
				'Postcode' => $m->postcode,
				'Plaats' => $m->plaats
			];
		}
		
		// Create and export Excel sheet from data
		\Excel::create(date('Y-m-d') . ' Nachtregister ' . $event->location->plaats . ' ' . $event->code, function($excel) use($pdata,$mdata) {
			$excel->sheet('Deelnemers', function($sheet) use($pdata) {
				$sheet->fromArray($pdata);
			});
			$excel->sheet('Leiding', function($sheet) use($mdata) {
				$sheet->fromArray($mdata);
			});
		})->export('xlsx');
	}
	
	# Generate iCal stream of events
	public function iCalendar() {
		$events = Event::orderBy('datum_start', 'asc')->where('openbaar', 1)->get();
		$members = Member::whereIn('soort', ['normaal', 'aspirant'])->get();
		
		foreach ($members as $key => $member) {
			$fullname = $member->voornaam . ' ';
			if ($member->tussenvoegsel != '') {
				$fullname .= $member->tussenvoegsel . ' ';
			}
			$fullname .= $member->achternaam;
			//$fullname = html_entity_decode($fullname, ENT_QUOTES | ENT_HTML401, 'UTF-8');
			$fullname = iconv('UTF-8','ASCII//TRANSLIT',$fullname);
			$members[$key]->naam = $fullname;
		}
		
		return response()
			->view('events.ical', compact('events', 'members'))
			//->header('Content-Type','text/plain; charset=utf-8');
			->header('Content-Type','text/calendar; charset=utf-8')
			->header('Content-Disposition', 'inline; filename=anderwijs.ics');
	}
	
	# Join members to event (form)
	public function joinMembers(Event $event) {
		$members = Member::where('soort', '<>', 'oud')->orderBy('voornaam')->get();
		
		return view('events.join-members', compact('event', 'members'));
	}
	
	# Join members to event (save)
	public function joinMembersSave(Event $event, Request $request) {
		foreach ($request->members as $member_id) {
			$event->members()->attach($member_id);
		}
		
		return redirect('events/'.$event->id)->with([
			'flash_message' => 'De leden zijn gekoppeld!'
		]);
	}
	
	# Show review results
	public function reviews(Event $event) {
		
		// Auth: either have to be admin or have gone on this camp as a member
		if (!(\Auth::user()->is_admin)) {
			
			$is_member = (\Auth::user()->profile_type == "App\Member");
			$on_camp = \Auth::user()->profile->events->contains($event->id);
			
			if (!($is_member && $on_camp)) {
				return redirect()->back();
			}
			
		}
		
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
