<?php

namespace App\Mail\members;

use App\Event;
use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class MemberRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $event;
    public $givenCourses;
    public $password;

    public function __construct(
        Member $member,
        Event $event,
        $givenCourses,
        $password
    ) {
        $this->member = $member;
        $this->event = $event;
        $this->givenCourses = $givenCourses;
        $this->password = $password;
    }

    public function build()
    {
        return $this->from([Config::get("mail.addresses.kamp")])
            ->to($this->member->email, $this->member->volnaam)
            ->subject("[Anderwijs] Bevestiging van inschrijving")
            ->view('emails.members.registrationConfirmation');
    }
}
