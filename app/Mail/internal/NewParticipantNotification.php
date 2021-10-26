<?php

declare(strict_types=1);

namespace App\Mail\internal;

use App\Event;
use App\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class NewParticipantNotification extends Mailable
{
    use Queueable;

    use SerializesModels;

    public $participant;

    public $event;

    public function __construct(Participant $participant, Event $event)
    {
        $this->participant = $participant;
        $this->event = $event;
    }

    public function build()
    {
        $subject = sprintf('%s Nieuwe deelnemer', Config::get('mail.subject_prefix.internal'));

        return $this->view('emails.internal.newParticipantNotification')
            ->from([Config::get('mail.addresses.aas')])
            ->to([Config::get('mail.addresses.kantoor')])
            ->subject($subject);
    }
}
