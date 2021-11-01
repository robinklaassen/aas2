<?php

declare(strict_types=1);

namespace App\Mail\participants;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class OnEventConfirmation extends Mailable
{
    use Queueable;

    use SerializesModels;

    public $participant;

    public $event;

    public $givenCourses;

    public $toPay;

    public $iDeal;

    public $type;

    public function __construct(Participant $participant, Event $event, $givenCourses, $toPay, $iDeal, $type)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->givenCourses = $givenCourses;
        $this->toPay = $toPay;
        $this->iDeal = $iDeal;
        $this->type = $type;
    }

    public function build()
    {
        $subject = sprintf('%s Bevestiging van aanmelding', Config::get('mail.subject_prefix.external'));

        return $this->view('emails.participants.onEventConfirmation')
            ->from([Config::get('mail.addresses.kantoor')])
            ->to([$this->participant->getParentEmail()])
            ->subject($subject);
    }
}
