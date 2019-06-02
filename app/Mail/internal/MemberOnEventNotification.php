<?php

namespace App\Mail\internal;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MemberOnEventNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;
    public $member;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Member $member, Event $event)
    {
        $this->member = $member;
        $this->event = $event;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.internal.memberOnEventNotification')
            ->from([Config::get("mail.addresses.aas")])
            ->to([Config::get("mail.addresses.kamp")])
            ->subject('AAS 2.0 - Lid op kamp');
    }
}
