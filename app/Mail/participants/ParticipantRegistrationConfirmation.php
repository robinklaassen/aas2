<?php

declare(strict_types=1);

namespace App\Mail\participants;

use App\Helpers\Payment\EventPayment;
use App\Models\Event;
use App\Models\EventPackage;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class ParticipantRegistrationConfirmation extends Mailable
{
    use Queueable;

    use SerializesModels;

    public ?EventPackage $package;

    public Participant $participant;

    public Event $event;

    public EventPayment $payment;

    public $givenCourses;

    public $password;

    public $iDeal;

    public function __construct(Participant $participant, Event $event, ?EventPackage $package, EventPayment $payment, $givenCourses, $password, $iDeal)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->givenCourses = $givenCourses;
        $this->password = $password;
        $this->iDeal = $iDeal;
        $this->package = $package;
        $this->payment = $payment;
    }

    public function build()
    {
        $subject = sprintf('%s Bevestiging van inschrijving', Config::get('mail.subject_prefix.external'));

        return $this->view('emails.participants.registrationConfirmation')
            ->to([$this->participant->getParentEmail()])
            ->from([Config::get('mail.addresses.kantoor')])
            ->subject($subject);
    }
}
