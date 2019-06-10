<?php

namespace App\Mail\internal;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Event;

class NewMemberNotification extends Mailable
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
        return $this->from("aas@anderwijs.nl", "ANDERWIJS - AAS")
            ->subject("AAS 2.0 - Nieuwe vrijwilliger")
            ->to([Config::get("email.addresses.kamp")])
            ->view('emails.newMemberNotification');
    }
}
