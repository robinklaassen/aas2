<?php

declare(strict_types=1);

namespace App\Mail\participants;

use App\Helpers\Payment\EventPayment;
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

    public Participant $participant;

    public Event $event;

    public EventPayment $payment;

    public $givenCourses;

    public $iDeal;

    public $type;

    public function __construct(Participant $participant, Event $event, EventPayment $payment, $givenCourses, $iDeal, $type)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->givenCourses = $givenCourses;
        $this->iDeal = $iDeal;
        $this->type = $type;
        $this->payment = $payment;
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
