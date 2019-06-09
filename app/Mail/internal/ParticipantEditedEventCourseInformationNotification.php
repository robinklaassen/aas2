<?php

namespace App\Mail\internal;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Participant;
use App\Event;

class ParticipantEditedEventCourseInformationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $event;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event)
    {
        $this->participant = $participant;
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.internal.participantEditedEventCourseInformationNotification')
            ->from([Config::get("mail.addresses.aas")])
            ->to([Config::get("mail.addresses.kantoor")])
            ->subject('AAS 2.0 - Vakken voor kamp bewerkt');
    }
}
