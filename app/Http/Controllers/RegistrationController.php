<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Member;
use App\Participant;
use App\Event;
use App\Course;
use Mail;
use App\Mail\participants\ParticipantRegistrationConfirmation;
use App\Mail\internal\NewParticipantNotification;
use App\Helpers\Payment\EventPayment;
use App\Facades\Mollie;
use App\Mail\internal\NewMemberNotification;
use App\Mail\members\MemberRegistrationConfirmation;

class RegistrationController extends Controller
{

	public function __construct()
	{
		// You can only access these forms if you are not logged in
		$this->middleware('guest');
	}

	# Member registration form
	public function registerMember()
	{
		// List of future camps that are not full
		$camps = Event::where('type', 'kamp')
			->where('datum_voordag', '>', date('Y-m-d'))
			->where('openbaar', 1)
			->orderBy('datum_start', 'asc')
			->get();
		$camp_options = array();
		foreach ($camps as $camp) {
			$camp_options[$camp->id] = $camp->naam . ' ' . substr($camp->datum_start, 0, 4) . ' te ' . $camp->location->plaats . ' (' . $camp->datum_voordag->format('d-m-Y') . ')';
			if ($camp->vol) {
				$camp_options[$camp->id] .= ' - VOL';
				$camp_full[$camp->id] = 1;
			} else {
				$camp_full[$camp->id] = 0;
			}
		}

		// List of courses
		$course_options = Course::orderBy('naam')->pluck('naam', 'id')->toArray();
		$course_options = [0 => '-kies vak-'] + $course_options;

		// List of 'hoe bij Anderwijs' options (without 'anders, namelijk'!)
		$hoebij_options = [
			"Via bekenden",
			"Google advertentie",
			"Google zoekmachine",
			"Facebook",
			"Informatiemarkt",
			"Supermarktadvertentie",
			"Poster",
			"Nieuwsbrief school"
		];

		return view('registration.member', compact('camp_options', 'camp_full', 'course_options', 'hoebij_options'));
	}

	# Member registration handler
	public function storeMember(Requests\MemberRequest $request)
	{
		// Validation specific for registration (one time)
		$this->validate($request, [
			'vog' => 'required',
			'privacy' => 'required'
		]);

		// Process 'hoebij' options to one string
		$hb = $request->hoebij;

		$k = array_search("0", $hb);
		if ($k !== FALSE) {
			$hb[$k] = $request->hoebij_anders;
		}

		$hb_string = implode(", ", $hb);

		$request->merge(array('hoebij' => $hb_string));

		// Store member in database
		$member = Member::create($request->except('selected_camp', 'vak0', 'vak1', 'vak2', 'vak3', 'vak4', 'vak5', 'vak6', 'vak7', 'klas0', 'klas1', 'klas2', 'klas3', 'klas4', 'klas5', 'klas6', 'klas7', 'hoebij_anders', 'vog', 'privacy'));

		// Attach to camp
		$member->events()->attach($request->selected_camp);

		// Attach courses
		$givenCourses = [];

		$courseInput = [$request->vak0, $request->vak1, $request->vak2, $request->vak3, $request->vak4, $request->vak5, $request->vak6, $request->vak7];
		$levelInput = [$request->klas0, $request->klas1, $request->klas2, $request->klas3, $request->klas4, $request->klas5, $request->klas6, $request->klas7];

		foreach (array_unique($courseInput) as $i => $course) {
			if ($course != '0') {
				$member->courses()->sync([$course], false);
				$member->courses()->updateExistingPivot($course, ['klas' => $levelInput[$i]]);
				$givenCourses[] = ['naam' => Course::find($course)->naam, 'klas' => $levelInput[$i]];
			}
		}

		// Create username
		$thename = strtolower(substr($member->voornaam, 0, 1) . str_replace(' ', '', $member->achternaam));
		$username = $thename;
		$nameList = \DB::table('users')->pluck('username');
		$i = 0;
		while ($nameList->contains($username)) {
			$i++;
			$username = $thename . $i;
		}

		// Create password
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password = substr(str_shuffle($chars), 0, 10);

		// Attach account
		$user = new \App\User;
		$user->username = $username;
		$user->password = bcrypt($password);
		$member->user()->save($user);

		$camp = Event::findOrFail($request->selected_camp);

		// Send confirmation email to newly registered member
		Mail::send(
			new MemberRegistrationConfirmation(
				$member,
				$camp,
				$givenCourses,
				$password
			)
		);

		// Send update to camp committee
		Mail::send(
			new NewMemberNotification(
				$member,
				$camp
			)
		);

		// Return closing view
		return view('registration.memberStored');
	}

	# Participant registration form
	public function registerParticipant()
	{
		// List of future camps that are not full
		$camps = Event::where('type', 'kamp')->where('datum_start', '>', date('Y-m-d'))->where('openbaar', 1)->orderBy('datum_start', 'asc')->get();
		$camp_options = array();
		foreach ($camps as $camp) {
			$camp_options[$camp->id] = $camp->naam . ' ' . substr($camp->datum_start, 0, 4) . ' te ' . $camp->location->plaats . ' (' . $camp->datum_start->format('d-m-Y') . ')';
			if ($camp->vol) {
				$camp_options[$camp->id] .= ' - VOL';
				$camp_full[$camp->id] = 1;
			} else {
				$camp_full[$camp->id] = 0;
			}
		}

		// List of courses
		$course_options = Course::orderBy('naam')->pluck('naam', 'id')->toArray();
		$course_options = [0 => '-kies vak-'] + $course_options;

		// List of 'hoe bij Anderwijs' options (without 'anders, namelijk'!)
		$hoebij_options = [
			"Familielid is eerder meegeweest",
			"Via bekenden",
			"Google zoekmachine",
			"Facebook (via vrienden)",
			"Facebook advertentie",
			"Bijlesvergelijker",
			"Online advertentie",
			"Poster",
			"Nieuwsbrief school",
			"Krant"
		];

		// Scramble the options!
		shuffle($hoebij_options);

		return view('registration.participant', compact('camp_options', 'camp_full', 'course_options', 'hoebij_options'));
	}

	# Participant registration handler
	public function storeParticipant(Requests\ParticipantRequest $request)
	{
		// Validation done in Request\ParticipantRequest
		// Additional one-time validation items here
		$this->validate($request, [
			'voorwaarden' => 'required',
			'privacy' => 'required'
		]);

		// Process 'hoebij' options to one string
		$hb = $request->hoebij;

		$k = array_search("0", $hb);
		if ($k !== FALSE) {
			$hb[$k] = $request->hoebij_anders;
		}

		$hb_string = implode(", ", $hb);

		$request->merge(array('hoebij' => $hb_string));

		// Store participant in database
		$participant = Participant::create($request->except('selected_camp', 'vak0', 'vak1', 'vak2', 'vak3', 'vak4', 'vak5', 'vakinfo0', 'vakinfo1', 'vakinfo2', 'vakinfo3', 'vakinfo4', 'vakinfo5', 'ideal', 'hoebij_anders', 'voorwaarden', 'privacy'));

		// Attach to camp
		$participant->events()->attach($request->selected_camp);

		// Attach courses (with information)
		$givenCourses = [];

		$courseInput = [$request->vak0, $request->vak1, $request->vak2, $request->vak3, $request->vak4, $request->vak5];
		$courseInfo = [$request->vakinfo0, $request->vakinfo1,  $request->vakinfo2, $request->vakinfo3, $request->vakinfo4, $request->vakinfo5];

		foreach (array_unique($courseInput) as $key => $course_id) {
			if ($course_id != 0) {
				\DB::table('course_event_participant')->insert(
					['course_id' => $course_id, 'event_id' => $request->selected_camp, 'participant_id' => $participant->id, 'info' => $courseInfo[$key]]
				);
				$givenCourses[] = ['naam' => Course::find($course_id)->naam, 'info' => $courseInfo[$key]];
			}
		}

		// Create username
		$thename = strtolower(substr($participant->voornaam, 0, 1) . str_replace(' ', '', $participant->achternaam));
		$username = $thename;
		$nameList = \DB::table('users')->pluck('username');
		$i = 0;
		while ($nameList->contains($username)) {
			$i++;
			$username = $thename . $i;
		}

		// Create password
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password = substr(str_shuffle($chars), 0, 10);

		// Attach account
		$user = new \App\User;
		$user->username = $username;
		$user->password = bcrypt($password);
		$participant->user()->save($user);

		// Income table
		$incomeTable = Participant::INCOME_DESCRIPTION_TABLE;

		// Obtain camp and cost information
		$camp = Event::findOrFail($request->selected_camp);
		$payment = (new EventPayment())
			->event($camp)
			->participant($participant)
			->existing(false);
		$toPay = $payment->getTotalAmount();
		$ideal = $request->ideal;

		// Send update to office committee
		Mail::send(new NewParticipantNotification(
			$participant,
			$camp
		));

		// Send confirmation email to newly registered participant's parent
		Mail::send(
			new ParticipantRegistrationConfirmation(
				$participant,
				$camp,
				$givenCourses,
				$password,
				$toPay,
				$ideal
			)
		);

		// If they want to pay with iDeal, set up the payment now
		if ($ideal == '1' && $camp->prijs != 0) {
			return Mollie::process($payment);
		} else {
			// Return closing view
			return view('registration.participantStored', compact('participant', 'camp', 'toPay', 'incomeTable'));
		}
	}
}
