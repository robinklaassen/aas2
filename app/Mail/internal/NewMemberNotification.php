<?php

namespace App\Mail\internal;

use App\Event;
use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
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
        $subject = sprintf('%s Nieuwe vrijwilliger', Config::get('mail.subject_prefix.internal'));
        
        return $this->from("aas@anderwijs.nl", "ANDERWIJS - AAS")
            ->subject($subject)
            ->to([Config::get("mail.addresses.kamp")])
            ->view('emails.internal.newMemberNotification');
    }
}
