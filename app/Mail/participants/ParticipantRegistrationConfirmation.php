<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Participant;
use App\Event;

class ParticipantRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $event;
    public $givenCourses;
    public $password;
    public $toPay;
    public $iDeal;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event, $givenCourses, $password, $toPay, $iDeal)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->givenCourses = $givenCourses;
        $this->password = $password;
        $this->toPay = $toPay;
        $this->iDeal = $iDeal;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $from = Config::get("mail.addresses.kantoor");
        return $this->view('emails.participants.registrationConfirmation')
            ->to($this->participant->getParentEmail())
            ->from($from->email, $from->name)
            ->subject('ANDERWIJS - Bevestiging van inschrijving');
    }
}
