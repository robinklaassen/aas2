<?php

namespace App\Mail\participants;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Participant;
use App\Event;

class IDealConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Participant $participant, Event $event, $type)
    {
        $this->participant = $participant;
        $this->event = $event;
        $this->type = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.participants.iDealConfirmation')
            ->from(Config::get("mail.addresses.kantoor"))
            ->to([$this->participants->getParentEmail()])
            ->subject('ANDERWIJS - Betaling via iDeal ontvangen');
    }
}
