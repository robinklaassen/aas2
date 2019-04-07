<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Input;
use App\Member;
use App\Participant;
use App\Event;
use App\Course;
use Illuminate\Http\Request;
use Mail;

class RegistrationController extends Controller {

	public function __construct()
	{
		// You can only access these forms if you are not logged in
		$this->middleware('guest');
	}

	# Member registration form
	public function registerMember()
	{
		// List of future camps that are not full
		$camps = Event::where('type', 'kamp')->where('datum_voordag','>',date('Y-m-d'))->where('openbaar', 1)->orderBy('datum_start', 'asc')->get();
		foreach ($camps as $camp)
		{
			$camp_options[$camp->id] = $camp->naam . ' ' . substr($camp->datum_start,0,4) . ' te ' . $camp->location->plaats . ' (' . $camp->datum_voordag->format('d-m-Y') . ')';
			if ($camp->vol) {
				$camp_options[$camp->id] .= ' - VOL';
				$camp_full[$camp->id] = 1;
			} else {
				$camp_full[$camp->id] = 0;
			}
		}
		
		// List of courses
		$course_options = Course::orderBy('naam')->lists('naam', 'id')->toArray();
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
		
		foreach (array_unique($courseInput) as $i => $course)
		{
			if ($course != '0')
			{
				$member->courses()->sync([$course], false);
				$member->courses()->updateExistingPivot($course, ['klas' => $levelInput[$i]]);
				$givenCourses[] = ['naam' => Course::find($course)->naam, 'klas' => $levelInput[$i]];
			}
		}
		
		// Create username
		$thename = strtolower(substr($member->voornaam,0,1) . str_replace(' ', '', $member->achternaam));
		$username = $thename;
		$nameList = \DB::table('users')->lists('username');
		$i = 0;
		while (in_array($username, $nameList))
		{
			$i++;
			$username = $thename . $i;
		}
		
		// Create password
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password = substr( str_shuffle( $chars ), 0, 10 );
		
		// Attach account
		$user = new \App\User;
		$user->username = $username;
		$user->password = bcrypt($password);
		$member->user()->save($user);
		
		$camp = Event::findOrFail($request->selected_camp);
		
		// Send confirmation email to newly registered member
		Mail::send('emails.newMemberConfirm', compact('member', 'camp', 'givenCourses', 'username', 'password'), function($message) use ($member)
		{
			$message->from('kamp@anderwijs.nl', 'Kampcommissie Anderwijs');
			
			$message->to($member->email, $member->voornaam . ' ' . $member->tussenvoegsel . ' ' . $member->achternaam);
			
			$message->subject('ANDERWIJS - Bevestiging van inschrijving');
		});
		
		// Send update to camp committee
		Mail::send('emails.newMemberNotification', ['member' => $member, 'camp' => $camp], function($message)
		{
			$message->to('kamp@anderwijs.nl', 'Kampcommissie Anderwijs')->subject('AAS 2.0 - Nieuwe vrijwilliger');
		});
		
		// Return closing view
		return view('registration.memberStored');
	}
	
	# Participant registration form
	public function registerParticipant()
	{
		// List of future camps that are not full
		$camps = Event::where('type', 'kamp')->where('datum_start','>',date('Y-m-d'))->where('openbaar', 1)->orderBy('datum_start', 'asc')->get();
		foreach ($camps as $camp)
		{
			$camp_options[$camp->id] = $camp->naam . ' ' . substr($camp->datum_start,0,4) . ' te ' . $camp->location->plaats . ' (' . $camp->datum_start->format('d-m-Y') . ')';
			if ($camp->vol) {
				$camp_options[$camp->id] .= ' - VOL';
				$camp_full[$camp->id] = 1;
			} else {
				$camp_full[$camp->id] = 0;
			}
		}
		
		// List of courses
		$course_options = Course::orderBy('naam')->lists('naam', 'id')->toArray();
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
		$participant = Participant::create($request->except('selected_camp', 'vak0', 'vak1', 'vak2', 'vak3', 'vak4', 'vak5', 'vakinfo0', 'vakinfo1', 'vakinfo2', 'vakinfo3', 'vakinfo4', 'vakinfo5', 'iDeal', 'hoebij_anders', 'voorwaarden', 'privacy'));
		
		// Attach to camp
		$participant->events()->attach($request->selected_camp);
		
		// Attach courses (with information)
		$givenCourses = [];
		
		$courseInput = [$request->vak0, $request->vak1, $request->vak2, $request->vak3, $request->vak4, $request->vak5];
		$courseInfo = [$request->vakinfo0, $request->vakinfo1,  $request->vakinfo2, $request->vakinfo3, $request->vakinfo4, $request->vakinfo5];
		
		foreach (array_unique($courseInput) as $key => $course_id)
		{
			if ($course_id != 0)
			{
				\DB::table('course_event_participant')->insert(
					['course_id' => $course_id, 'event_id' => $request->selected_camp, 'participant_id' => $participant->id, 'info' => $courseInfo[$key]]
				);
				$givenCourses[] = ['naam' => Course::find($course_id)->naam, 'info' => $courseInfo[$key]];
			}
		}
		
		// Create username
		$thename = strtolower(substr($participant->voornaam,0,1) . str_replace(' ', '', $participant->achternaam));
		$username = $thename;
		$nameList = \DB::table('users')->lists('username');
		$i = 0;
		while (in_array($username, $nameList))
		{
			$i++;
			$username = $thename . $i;
		}
		
		// Create password
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password = substr( str_shuffle( $chars ), 0, 10 );
		
		// Attach account
		$user = new \App\User;
		$user->username = $username;
		$user->password = bcrypt($password);
		$participant->user()->save($user);
		
		// Income table
		$incomeTable = [
			0 => 'Meer dan € 3400 (geen korting)',
			1 => 'Tussen € 2200 en € 3400 (korting: 15%)',
			2 => 'Tussen € 1300 en € 2200 (korting: 30%)',
			3 => 'Minder dan € 1300 (korting: 50%)'
		];
		
		// Obtain camp and cost information
		$camp = Event::findOrFail($request->selected_camp);
		switch ($participant->inkomen)
		{
			case 0:
				$toPay = $camp->prijs;
				break;
			
			case 1:
				$toPay = round((0.85 * $camp->prijs)/5) * 5;
				break;
				
			case 2:
				$toPay = round((0.7 * $camp->prijs)/5) * 5;
				break;
				
			case 3:
				$toPay = round((0.5 * $camp->prijs)/5) * 5;
				break;
		}
		
		// Send update to office committee
		Mail::send('emails.newParticipantNotification', ['participant' => $participant, 'camp' => $camp], function($message)
		{
			$message->to('kantoor@anderwijs.nl', 'Kantoorcommissie Anderwijs')->subject('AAS 2.0 - Nieuwe deelnemer');
		});
		
		$iDeal = $request->iDeal;
		
		// Send confirmation email to newly registered participant
		Mail::send('emails.newParticipantConfirm', compact('participant', 'camp', 'givenCourses', 'username', 'password', 'incomeTable', 'toPay', 'iDeal'), function($message) use ($participant)
		{
			$message->from('kantoor@anderwijs.nl', 'Kantoorcommissie Anderwijs');
			
			$message->to($participant->email_ouder, 'dhr./mw. ' . $participant->tussenvoegsel . ' ' . $participant->achternaam)->subject('ANDERWIJS - Bevestiging van inschrijving');
		});
		
		// If they want to pay with iDeal, set up the payment now
		if ($iDeal == '1' && $camp->prijs != 0)
		{
			// Initialize Mollie (with API key)
			include "MollieSet.php";
			
			// Create the payment
			$payment = $mollie->payments->create(array(
				"amount"      => $toPay,
				"description" => $camp->code . " - " . str_replace("  ", " ", $participant->voornaam . " " . $participant->tussenvoegsel . " " . $participant->achternaam),
				"metadata"	  => array(
					//"order_id" => $order_id
					"participant_id" => $participant->id,
					"camp_id" => $camp->id,
					"type" => "new"
				),
				"webhookUrl"  => "https://aas2.anderwijs.nl/iDeal-webhook",
				"redirectUrl" => "https://aas2.anderwijs.nl/iDeal-response/{$participant->id}/{$camp->id}",
				"method" => \Mollie_API_Object_Method::IDEAL,
			));
			
			// Direct to Mollie payment site
			return redirect($payment->getPaymentUrl());
		}
		else
		{
			// Return closing view
			return view('registration.participantStored', compact('participant', 'camp', 'toPay', 'incomeTable'));
		}
		
	}

}
