<?php

namespace App\Mail\internal;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Member;
use App\Event;
use Illuminate\Support\Facades\Config;

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
            ->to([Config::get("mail.addresses.kamp")])
            ->view('emails.internal.newMemberNotification');
    }
}
