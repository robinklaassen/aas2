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
            $camps = Event::ParticipantEvent()
                ->ongoing()
                ->notCancelled()
                ->public()
                ->orderBy('datum_start')
                ->take(2)
                ->get();
            $thermo = [];
            foreach ($camps as $camp) {
                $streef_L = $camp->streeftal;
                $streef_D = ($streef_L - 1) * 3;

                // Hack for 'double camp' event (Winterkampen 2017-2018)
                if ($id === 129) {
                    $streef_L = 12;
                    $streef_D = 28;
                }

                $num_L_goed = $camp->members()->wherePivot('wissel', 0)->where('vog', 1)->count();
                $num_L_bijna = $camp->members()->wherePivot('wissel', 0)->where('vog', 0)->count();

                $num_D_goed = $camp->participants()->wherePivot('datum_betaling', '>', '0000-00-00')->count();
                $num_D_bijna = $camp->participants()->wherePivot('datum_betaling', '0000-00-00')->count();

                $perc_L_goed = ($num_L_goed > $streef_L) ? 100 : ($num_L_goed / $streef_L) * 100;
                $perc_D_goed = ($num_D_goed > $streef_D) ? 100 : ($num_D_goed / $streef_D) * 100;

                $perc_L_bijna = ($num_L_goed + $num_L_bijna > $streef_L) ? 100 - $perc_L_goed : ($num_L_bijna / $streef_L) * 100;
                $perc_D_bijna = ($num_D_goed + $num_D_bijna > $streef_D) ? 100 - $perc_D_goed : ($num_D_bijna / $streef_D) * 100;

                $thermo[] = compact('camp', 'streef_L', 'streef_D', 'num_L_goed', 'perc_L_goed', 'num_L_bijna', 'perc_L_bijna', 'num_D_goed', 'perc_D_goed', 'num_D_bijna', 'perc_D_bijna');
            }

            return view('pages.home-member', compact('today', 'thermo'));
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
