<?php

namespace App\Mail\members;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Member;
use App\Event;
use Illuminate\Support\Facades\Config;

class OnEventConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $event;

    public function __construct(Member $member, Event $event)
    {
        $this->member = $member;
        $this->event = $event;
    }

    public function build()
    {
        return $this->view('emails.members.onEventConfirmation')
            ->subject("AAS 2.0 - Aangemeld voor kamp")
            ->from([Config::get("mail.addresses.aas")])
            ->to($this->member->email_anderwijs, $this->member->volnaam);
    }
}
