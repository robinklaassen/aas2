<?php

namespace App\Mail\participants;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Participant;
use App\Event;
use App\EventPackage;
use Illuminate\Support\Facades\Config;

class ParticipantRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $package;
    public $participant;
    public $event;
    public $givenCourses;
    public $password;
    public $toPay;
    public $iDeal;

    public function __construct(Participant $participant, Event $event, ?EventPackage $package, $givenCourses, $password, $toPay, $iDeal)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->givenCourses = $givenCourses;
        $this->password = $password;
        $this->toPay = $toPay;
        $this->iDeal = $iDeal;
        $this->package = $package;
    }

    public function build()
    {
        $subject = sprintf('%s Bevestiging van inschrijving', Config::get('mail.subject_prefix.external'));

        return $this->view('emails.participants.registrationConfirmation')
            ->to([$this->participant->getParentEmail()])
            ->from([Config::get("mail.addresses.kantoor")])
            ->subject($subject);
    }
}
