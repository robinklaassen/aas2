<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Facades\Mollie;
use App\Mail\participants\IDealConfirmation;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mollie\Api\Resources\Payment;

class iDealController extends Controller
{
    public function webhook(Request $request)
    {
        // Retrieve payment info
        /** @var Payment $payment */
        $payment = Mollie::api()->payments->get($request->id);
        if (isset($payment->metadata->camp_id)) {
            $this->handleCampPayment($payment);
        }
    }

    // iDeal response page
    public function eventPaymentResponse(Participant $participant, Event $event)
    {
        $camp = $participant->events()->whereId($event->id)->first();
        $status = ($camp->pivot->datum_betaling === '0000-00-00') ? 'failed' : 'ok';

        return view('registration.iDealResponse', compact('status', 'participant'));
    }

    public function genericResponse()
    {
        return view('registration.iDealResponse', [
            'status' => 'ok',
        ]);
    }

    private function handleCampPayment(Payment $payment): void
    {
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

            Mail::send(
                new IDealConfirmation(
                    $participant,
                    $camp,
                    $type
                )
            );
        }
    }
}
