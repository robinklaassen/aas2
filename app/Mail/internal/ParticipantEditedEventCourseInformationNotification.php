<?php

declare(strict_types=1);

namespace App\Mail\internal;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class ParticipantEditedEventCourseInformationNotification extends Mailable
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
        $subject = sprintf('%s Vakken voor kamp bewerkt', Config::get('mail.subject_prefix.internal'));

        return $this->view('emails.internal.participantEditedEventCourseInformationNotification')
            ->from([Config::get('mail.addresses.aas')])
            ->to([Config::get('mail.addresses.kantoor')])
            ->subject($subject);
    }
}
