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

            $birthdays = Member::where('soort', '<>', 'oud')->where('publish_birthday', 1)->birthday()->get();

            $thermometerCamps = Event::ParticipantEvent()
                ->ongoing()
                ->notCancelled()
                ->public()
                ->orderBy('datum_start')
                ->take(2)
                ->get();

            return view('pages.home-member', compact('birthdays', 'thermometerCamps'));
        } elseif ($request->user()->isParticipant()) {
            // Participant homepage
            $participant = $request->user()->profile;
            $registeredCamps = $participant->events()->onGoing()->notCancelled()->get();
            return view('pages.home-participant', compact('participant', 'registeredCamps'));
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
