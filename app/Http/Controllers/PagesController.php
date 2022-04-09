<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;

// The PagesController is for static pages that do not fall under any other type of controller, like members or events.
class PagesController extends Controller
{
    // Homepage
    public function home(Request $request)
    {
        if ($request->user()->isMember()) {
            // Member homepage

            // Today's birthdays
            $bdates = Member::where('soort', '<>', 'oud')->where('publish_birthday', 1)->pluck('geboortedatum', 'id')->toArray();
            $today = [];
            foreach ($bdates as $id => $date) {
                if ($date->isBirthday()) {
                    $today[] = $id;
                }
            }

            foreach ($today as $k => $id) {
                $member = Member::find($id);
                $member->leeftijd = date('Y') - $member->geboortedatum->year;
                $member->ikjarig = false;
                if ($request->user()->profile->id === $id) {
                    $member->ikjarig = true;
                }
                $today[$k] = $member;
            }

            // Camp thermometer
            $thermometerCamps = Event::ParticipantEvent()
                ->ongoing()
                ->notCancelled()
                ->public()
                ->orderBy('datum_start')
                ->take(2)
                ->get();

            return view('pages.home-member', compact('today', 'thermometerCamps'));
        } elseif ($request->user()->isParticipant()) {
            // Participant homepage

            // Birthday check!
            $bday = $request->user()->profile->geboortedatum;
            $congrats = ($bday->isBirthday()) ? 1 : 0;

            return view('pages.home-participant', compact('congrats'));
        }
    }

    public function showPrivacyStatement()
    {
        return view('pages.privacy-statement');
    }

    public function showAcceptPrivacyStatement(Request $request)
    {
        $user = $request->user();
        $showForm = true;
        return view('pages.privacy-statement', compact('user', 'showForm'));
    }

    public function storePrivacyStatement(Request $request)
    {
        $privacyAccepted = $request->input('privacyAccepted') === '1';
        if (! $privacyAccepted) {
            return redirect('accept-privacy')->with([
                'flash_error' => 'De privacyvoorwaarden dienen geaccepteerd te worden om verder te kunnen.',
            ]);
        }

        $user = $request->user();
        $user->privacy = Carbon::now();
        $user->save();
        return redirect('home');
    }
}
