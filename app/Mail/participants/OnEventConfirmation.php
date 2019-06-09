<?php

namespace App\Mail\participants;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Event;

class OnEventConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $event;
    public $givenCourses;
    public $toPay;
    public $iDeal;
    public $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event, $givenCourses, $toPay, $iDeal, $type)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->givenCourses = $givenCourses;
        $this->toPay = $toPay;
        $this->iDeal = $iDeal;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.participants.onEventConfirmation')
            ->from([Config::get("mail.addresses.kantoor")])
            ->to([$this->participant->getParentEmail()])
            ->subject('ANDERWIJS - Bevestiging van aanmelding');
    }
}
