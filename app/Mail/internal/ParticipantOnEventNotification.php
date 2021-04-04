<?php

namespace App\Mail\internal;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Participant;
use App\Event;
use Illuminate\Support\Facades\Config;

class ParticipantOnEventNotification extends Mailable
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
        $subject = sprintf('%s Deelnemer op kamp', Config::get('mail.subject_prefix.internal'));

        return $this->view('emails.internal.participantOnEventNotification')
            ->subject($subject)
            ->to([Config::get("mail.addresses.kantoor")])
            ->from([Config::get("mail.addresses.aas")]);
    }
}
