<?php

declare(strict_types=1);

namespace App\Mail\participants;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class IDealConfirmation extends Mailable
{
    use Queueable;

    use SerializesModels;

    public $participant;

    public $event;

    public $type;

    public function __construct(Participant $participant, Event $event, $type)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->type = $type;
    }

    public function build()
    {
        $subject = sprintf('%s Betaling via iDeal ontvangen', Config::get('mail.subject_prefix.external'));

        return $this->view('emails.participants.iDealConfirmation')
            ->from(Config::get('mail.addresses.kantoor'))
            ->to([$this->participant->getParentEmail()])
            ->subject($subject);
    }
}
