<?php

namespace App\Mail\internal;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewParticipantNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $event;

    public function __construct(Participant $participant, Event $event)
    {
        $this->participant = $participant;
        $this->event = $event;
    }

    public function build()
    {
        return $this->view('emails.newParticipantNotification')
            ->from([Config::get("mail.addresses.aas")])
            ->to([Config::get("mail.addresses.kantoor")])
            ->subject('AAS 2.0 - Nieuwe deelnemer');;
    }
}
