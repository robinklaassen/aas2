<?php

namespace App\Http\Controllers;

use App\User;
use App\Event;
use App\Participant;
use App\Http\Requests\ParticipantRequest;
use App\Http\Requests\AnonymizeParticipantRequest;
use App\Http\Controllers\Controller;
use App\Exports\ParticipantsExport;
use App\Services\Anonymize\AnonymizeParticipantInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ParticipantsController extends Controller
{

    /**
     * @var AnonymizeParticipantInterface
     */
    private $anonymizeParticipant;

    public function __construct(AnonymizeParticipantInterface $anonymizeParticipant)
	{
		$this->authorizeResource(Participant::class, 'participant');
        $this->anonymizeParticipant = $anonymizeParticipant;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$participants = Participant::nonAnonymized()->get();
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
	public function store(ParticipantRequest $request)
	{
		Participant::create($request->all());
		return redirect('participants')->with([
			'flash_message' => 'De deelnemer is aangemaakt!'
		]);
	}

	/**
	 * Display the specified resource.
	 *
	 */
	public function show(Participant $participant, string $viewType = 'admin')
	{
		// Make a 'courses on camp' array
		$courseOnCamp = [];
		foreach ($participant->events as $event) {
			$courseOnCamp[$event->id] = [];
		}
		$result = DB::table('course_event_participant')
			->where('participant_id', '=', $participant->id)
			->join('courses', 'course_event_participant.course_id', '=', 'courses.id')
			->orderBy('courses.naam')
			->get();
		foreach ($result as $row) {
			$courseOnCamp[$row->event_id][] = ['id' => $row->course_id, 'naam' => $row->naam, 'code' => $row->code];
		}

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
	public function update(Participant $participant, ParticipantRequest $request)
	{
		$participant->update($request->all());
		return redirect('participants/' . $participant->id)->with([
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
		$this->authorize("delete", $participant);
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
		$this->authorize("onEvent", $participant);
		return view('participants.onEvent', compact('participant'));
	}

	# Send participant on event (update database)
	public function onEventSave(Participant $participant, Request $request)
	{
		$this->authorize("onEvent", $participant);
		$status = $participant->events()->sync([$request->selected_event], false);
		if ($status['attached'] != []) {
			// Insert courses
			foreach (array_unique($request->vak) as $key => $course_id) {
				if ($course_id) {
					DB::table('course_event_participant')->insert(
						['course_id' => $course_id, 'event_id' => $request->selected_event, 'participant_id' => $participant->id, 'info' => $request->vakinfo[$key]]
					);
				}
			}
		}
		$message = ($status['attached'] == []) ? 'De deelnemer is reeds op dit evenement gestuurd!' : 'De deelnemer is op evenement gestuurd!';
		return redirect('participants/' . $participant->id)->with([
			'flash_message' => $message
		]);
	}

	# Edit participant on event (form)
	public function editEvent(Participant $participant, Event $event)
	{
		$this->authorize("editParticipant", [$event, $participant]);
		$result = DB::table('course_event_participant')->select('course_id', 'info')->whereParticipantIdAndEventId($participant->id, $event->id)->get();
		$retrieved_courses = [];
		foreach ($result as $row) {
			$retrieved_courses[] = ['id' => $row->course_id, 'info' => $row->info];
		}
		return view('participants.editEvent', compact('participant', 'event', 'retrieved_courses'));
	}

	# Edit participant on event (update database)
	public function editEventSave(Participant $participant, Event $event, Request $request)
	{
		$this->authorize("editParticipant", [$event, $participant]);

		// Delete all current courses
		DB::table('course_event_participant')->whereParticipantIdAndEventId($participant->id, $event->id)->delete();

		// Insert new courses
		foreach (array_unique($request->vak) as $key => $course_id) {
			if ($course_id) {
				DB::table('course_event_participant')->insert(
					['course_id' => $course_id, 'event_id' => $event->id, 'participant_id' => $participant->id, 'info' => $request->vakinfo[$key]]
				);
			}
		}

		return redirect('participants/' . $participant->id)->with([
			'flash_message' => 'De vakken voor dit kamp zijn bewerkt!'
		]);
	}

	# Export all participants to Excel
	public function export()
	{
		// 20200301, this exports holds all information for partcipants, lets first be clear for whom this export is.
		abort(403);

		return Excel::download(new ParticipantsExport, date('Y-m-d') . ' AAS Deelnemers.xlsx');
	}

	public function anonymize()
    {
        $this->authorize('anonymize', Participant::class);
        $participants = $this->anonymizeParticipant->getParticipantsToAnonymize(new \DateTimeImmutable());
        return view('participants.anonymize', compact('participants'));
    }
	public function anonymizeConfirm(AnonymizeParticipantRequest $request)
    {
        $this->authorize('anonymize', Participant::class);
        $participants = Participant::findMany($request->get('participant', []));
        return view('participants.anonymizeConfirm', compact('participants'));
    }

	public function anonymizeStore(AnonymizeParticipantRequest $request)
    {
        $this->authorize('anonymize',  Participant::class);
        $participants = Participant::findMany($request->get('participant', []));
        foreach ($participants as $participant) {
            $this->anonymizeParticipant->anonymize($participant);
        }

        return redirect(action('ParticipantsController@index'))->with([
            'flash_message' => count($participants) . ' geanonimiseerd'
        ]);
    }
}
