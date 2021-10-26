<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Event;
use App\Facades\Mollie;
use App\Mail\participants\IDealConfirmation;
use App\Participant;

use Illuminate\Http\Request;
use Mail;

// The iDealController is for the Mollie API iDeal webhook and response routes
class iDealController extends Controller
{
    // iDeal webhook
    public function webhook(Request $request)
    {

        // Retrieve payment info
        $payment = Mollie::api()->payments->get($request->id);
        $participant_id = $payment->metadata->participant_id;
        $camp_id = $payment->metadata->camp_id;
        $type = $payment->metadata->type;
        $participant = Participant::findOrFail($participant_id);
        $camp = Event::findOrFail($camp_id);

        if ($payment->isPaid()) {
            // Update payment status with today's date
            $participant->events()->updateExistingPivot($camp_id, [
                'datum_betaling' => date('Y-m-d'),
            ]);

            // Send (yet another) confirmation email to parents

            Mail::send(
                new IDealConfirmation(
                    $participant,
                    $camp,
                    $type
                )
            );
        }
    }

    // iDeal response page
    public function response(Participant $participant, Event $event)
    {
        $camp = $participant->events()->whereId($event->id)->first();
        $status = ($camp->pivot->datum_betaling === '0000-00-00') ? 'failed' : 'ok';

        return view('registration.iDealResponse', compact('status', 'participant'));
    }
}
