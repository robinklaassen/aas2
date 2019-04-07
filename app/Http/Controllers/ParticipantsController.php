<?php namespace App\Http\Controllers;

use App\Participant;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Input;
use Illuminate\Http\Request;

class ParticipantsController extends Controller {

	public function __construct()
	{
		// You need to be logged in and have admin rights to access
		$this->middleware('auth');
		$this->middleware('admin');
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$participants = Participant::all();
		return view('participants.index', compact('participants'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$viewType = 'admin';
		return view('participants.create', compact('viewType'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\ParticipantRequest $request)
	{
		Participant::create($request->all());
		return redirect('participants')->with([
			'flash_message' => 'De deelnemer is aangemaakt!'
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Participant $participant)
	{
		$viewType = 'admin';
		// Make a 'courses on camp' array
		$courseOnCamp = [];
		foreach ($participant->events as $event) {
			$courseOnCamp[$event->id] = [];
		}
		$result = \DB::table('course_event_participant')
			->where('participant_id', '=', $participant->id)
			->join('courses', 'course_event_participant.course_id', '=', 'courses.id')
			->orderBy('courses.naam')
			->get();
		foreach ($result as $row)
		{
			$courseOnCamp[$row->event_id][] = ['id' => $row->course_id, 'naam' => $row->naam, 'code' => $row->code];
		}
		//dd($participant->inkomensverklaring);
		return view('participants.show', compact('participant', 'viewType', 'courseOnCamp'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Participant $participant)
	{
		$viewType = 'admin';
		return view('participants.edit', compact('participant', 'viewType'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Participant $participant, Requests\ParticipantRequest $request)
	{
		$participant->update($request->all());
		return redirect('participants/'.$participant->id)->with([
			'flash_message' => 'De deelnemer is bewerkt!'
		]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 
	public function delete(Participant $participant)
	{
		return view('participants.delete', compact('participant'));
	}
	
	public function destroy(Participant $participant)
	{
		$participant->user()->delete();
		$participant->delete();
		return redirect('participants')->with([
			'flash_message' => 'De deelnemer is verwijderd!'
		]);
	}

	# Send participant on event (form)
	public function onEvent(Participant $participant)
	{
		return view('participants.onEvent', compact('participant'));
	}
	
	# Send participant on event (update database)
	public function onEventSave(Participant $participant, Request $request)
	{
		// Sync 'event_participant' pivot table
		$status = $participant->events()->sync([$request->selected_event], false);
		if ($status['attached']!=[])
		{
			// Insert courses
			foreach (array_unique($request->vak) as $key => $course_id)
			{
				if ($course_id)
				{
					\DB::table('course_event_participant')->insert(
						['course_id' => $course_id, 'event_id' => $request->selected_event, 'participant_id' => $participant->id, 'info' => $request->vakinfo[$key]]
					);
				}
			}
		}
		$message = ($status['attached']==[]) ? 'De deelnemer is reeds op dit evenement gestuurd!' : 'De deelnemer is op evenement gestuurd!';
		return redirect('participants/'.$participant->id)->with([
			'flash_message' => $message
		]);
	}
	
	# Edit participant on event (form)
	public function editEvent(Participant $participant, $event_id)
	{
		$event = \App\Event::findOrFail($event_id);
		$result = \DB::table('course_event_participant')->select('course_id', 'info')->whereParticipantIdAndEventId($participant->id, $event_id)->get();
		$retrieved_courses = [];
		foreach ($result as $row)
		{
			$retrieved_courses[] = ['id' => $row->course_id, 'info' => $row->info];
		}
		return view('participants.editEvent', compact('participant', 'event', 'retrieved_courses'));
	}
	
	# Edit participant on event (update database)
	public function editEventSave(Participant $participant, $event_id, Request $request)
	{
		// Delete all current courses
		\DB::table('course_event_participant')->whereParticipantIdAndEventId($participant->id, $event_id)->delete();
		
		// Insert new courses
		foreach (array_unique($request->vak) as $key => $course_id)
		{
			if ($course_id)
			{
				\DB::table('course_event_participant')->insert(
					['course_id' => $course_id, 'event_id' => $event_id, 'participant_id' => $participant->id, 'info' => $request->vakinfo[$key]]
				);
			}
		}
		
		return redirect('participants/'.$participant->id)->with([
			'flash_message' => 'De vakken voor dit kamp zijn bewerkt!'
		]);
	}
	
	# Export all participants to Excel
	public function export()
	{
		$participants = Participant::all();
		
		\Excel::create(date('Y-m-d').' AAS Deelnemers', function($excel) use($participants) {
			$excel->sheet('deelnemers', function($sheet) use($participants) {
				$sheet->fromModel($participants);
			});
		})->export('xlsx');
		
	}
	
	# Show all last years participants on a Google Map
	public function map()
	{
		// Create necessary dates
		$now = new \DateTime('now');
		$nowf = $now->format('Y-m-d');
		$yearago = $now->modify('-1 year')->format('Y-m-d');
		
		// Get all relevant camps
		$camps = \App\Event::where('type', 'kamp')->where('datum_eind', '>', $yearago)->where('datum_eind', '<', $nowf)->get();
		
		// Get participant id's and make list of camp names
		$pids = []; $campnames = [];
		foreach ($camps as $camp) 
		{
			$pids = array_merge($pids, $camp->participants()->lists('id')->toArray());
			$campnames[] = $camp->naam . ' ' . $camp->datum_start->format('Y');
		}
		
		// Get number of camps per participant id
		$pidfreq = array_count_values($pids);
		ksort($pidfreq);
		$amount = count($pidfreq);
		
		// Create participant data for map
		foreach ($pidfreq as $pid => $freq)
		{
			$p = Participant::find($pid);
			
			$markerURL = 'http://maps.google.com/mapfiles/ms/icons/';
			switch ($freq) {
				case 1:
					$markerURL .= 'red-dot.png';
					break;
				case 2:
					$markerURL .= 'green-dot.png';
					break;
				default:
					$markerURL .= 'blue-dot.png';
					break;
			}
			
			$participantData[] = [
				'address' => $p->adres . ', ' . $p->plaats,
				'name' => str_replace('  ', ' ', $p->voornaam . ' ' . $p->tussenvoegsel . ' ' . $p->achternaam),
				'markerURL' => $markerURL
			];
		}
		
		$participantJSON = json_encode($participantData);
		
		return view('participants.map', compact('campnames', 'amount', 'participantJSON'));
	}
}
