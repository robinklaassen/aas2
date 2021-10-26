<?php

namespace App\Http\Controllers;

use App\Course;

use App\Event;
use App\Facades\Mollie;
use App\Helpers\Payment\EventPayment;
use App\Http\Requests;
use App\Mail\internal\NewMemberNotification;
use App\Mail\internal\NewParticipantNotification;
use App\Mail\members\MemberRegistrationConfirmation;
use App\Mail\participants\ParticipantRegistrationConfirmation;
use App\Member;
use App\Participant;
use App\Role;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    # Member registration form
    public function registerMember()
    {
        // List of future camps that are not full
        $camps = Event::where('type', 'kamp')
            ->where('datum_voordag', '>', date('Y-m-d'))
            ->public()
            ->orderBy('datum_start', 'asc')
            ->get();

        $camp_options = [];
        $camp_full = [];
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
        if ($k !== false) {
            $hb[$k] = $request->hoebij_anders;
        }

        $hb_string = implode(", ", $hb);

        $request->merge(['hoebij' => $hb_string]);

        // Store member in database
        $member = Member::create($request->except('selected_camp', 'vak0', 'vak1', 'vak2', 'vak3', 'vak4', 'vak5', 'vak6', 'vak7', 'klas0', 'klas1', 'klas2', 'klas3', 'klas4', 'klas5', 'klas6', 'klas7', 'hoebij_anders', 'vog', 'privacy'));

        // Attach to camp
        $member->events()->attach($request->selected_camp);

        // Attach courses
        $givenCourses = [];

        $courseInput = [$request->vak0, $request->vak1, $request->vak2, $request->vak3, $request->vak4, $request->vak5, $request->vak6, $request->vak7];
        $levelInput = [$request->klas0, $request->klas1, $request->klas2, $request->klas3, $request->klas4, $request->klas5, $request->klas6, $request->klas7];

        foreach (array_unique($courseInput) as $i => $course) {
            if ($course !== null && $course != '0') {
                $member->courses()->sync([$course], false);
                $member->courses()->updateExistingPivot($course, ['klas' => $levelInput[$i]]);
                $givenCourses[] = ['naam' => Course::find($course)->naam, 'klas' => $levelInput[$i]];
            }
        }

        // Create username
        $thename = strtolower(substr($member->voornaam, 0, 1) . str_replace(' ', '', $member->achternaam));
        $username = $thename;
        $nameList = DB::table('users')->pluck('username');
        $i = 0;
        while ($nameList->contains($username)) {
            $i++;
            $username = $thename . $i;
        }

        $password = User::generatePassword();

        // Attach account
        $user = new \App\User();
        $user->username = $username;
        $user->password = bcrypt($password);
        $user->privacy = Carbon::now();
        $member->user()->save($user);

        $roles = Role::whereIn("tag", ["member"])->get();
        $user->roles()->sync($roles);

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
        $camps = Event::public()->open()->participantEvent()->orderBy('datum_start')->get();

        // List of courses
        $course_options = Course::orderBy('naam')->pluck('naam', 'id')->toArray();
        $course_options = [0 => '-kies vak-'] + $course_options;

        $package_type_per_camp = $camps->where('package_type', '!=', null)->mapWithKeys(function ($item) {
            return [$item['id'] => $item['package_type']];
        });

        $packages = \App\EventPackage::all()->groupBy('type');

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

        return view('registration.participant', compact('camps', 'course_options', 'hoebij_options', 'packages', 'package_type_per_camp'));
    }

    # Participant registration handler
    public function storeParticipant(Requests\NewParticipantRequest $request)
    {
        // Validation done in Request\NewParticipantRequest
        // Additional one-time validation items here
        $this->validate($request, [
            'voorwaarden' => 'required',
            'privacy' => 'required'
        ]);

        $package = \App\EventPackage::find($request->selected_package);
        $camp = Event::findOrFail($request->selected_camp);

        // Check given package for camp
        if ($camp['package_type'] !== null && ($package === null || $package['type'] !== $camp['package_type'])) {
            return back()->with([
                'flash_error' => 'Er dient een pakket geselecteerd te worden voor dit kamp'
            ]);
        } elseif ($camp['package_type'] === null) {
            // remove any given package if the camp doesn't accept packages
            $package = null;
        }

        // Process 'hoebij' options to one string
        $hb = $request->hoebij;

        $k = array_search("0", $hb);
        if ($k !== false) {
            $hb[$k] = $request->hoebij_anders;
        }

        $hb_string = implode(", ", $hb);

        $request->merge(['hoebij' => $hb_string]);

        // Store participant in database
        $participant = Participant::create($request->except('selected_package', 'selected_camp', 'vak0', 'vak1', 'vak2', 'vak3', 'vak4', 'vak5', 'vakinfo0', 'vakinfo1', 'vakinfo2', 'vakinfo3', 'vakinfo4', 'vakinfo5', 'iDeal', 'hoebij_anders', 'voorwaarden', 'privacy'));

        // Attach to camp
        $participant->events()->attach($request->selected_camp, ["package_id" => $package === null ? null : $package->id]);

        // Attach courses (with information)
        $givenCourses = [];

        $courseInput = [$request->vak0, $request->vak1, $request->vak2, $request->vak3, $request->vak4, $request->vak5];
        $courseInfo = [$request->vakinfo0, $request->vakinfo1,  $request->vakinfo2, $request->vakinfo3, $request->vakinfo4, $request->vakinfo5];

        foreach (array_unique($courseInput) as $key => $course_id) {
            if ($course_id != 0) {
                DB::table('course_event_participant')->insert(
                    ['course_id' => $course_id, 'event_id' => $request->selected_camp, 'participant_id' => $participant->id, 'info' => $courseInfo[$key]]
                );
                $givenCourses[] = ['naam' => Course::find($course_id)->naam, 'info' => $courseInfo[$key]];
            }
        }

        // Create username
        $thename = strtolower(substr($participant->voornaam, 0, 1) . str_replace(' ', '', $participant->achternaam));
        $username = $thename;
        $nameList = DB::table('users')->pluck('username');
        $i = 0;
        while ($nameList->contains($username)) {
            $i++;
            $username = $thename . $i;
        }

        // Create password
        $password = User::generatePassword();

        // Attach account
        $user = new \App\User();
        $user->username = $username;
        $user->password = bcrypt($password);
        $user->privacy = Carbon::now();
        $participant->user()->save($user);

        $roles = Role::whereIn("tag", ["participant"])->get();
        $user->roles()->sync($roles);

        // Income table
        $incomeTable = Participant::INCOME_DESCRIPTION_TABLE;

        // Obtain cost information
        $payment = (new EventPayment())
            ->event($camp)
            ->participant($participant)
            ->package($package)
            ->existing(false);
        $toPay = $payment->getTotalAmount();
        $iDeal = $request->iDeal;

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
                $package,
                $givenCourses,
                $password,
                $toPay,
                $iDeal
            )
        );

        // If they want to pay with iDeal, set up the payment now
        if ($iDeal == '1' && $toPay > 0) {
            return Mollie::process($payment);
        } else {
            // Return closing view
            return view('registration.participantStored', compact('participant', 'camp', 'toPay', 'incomeTable', 'package'));
        }
    }
}
