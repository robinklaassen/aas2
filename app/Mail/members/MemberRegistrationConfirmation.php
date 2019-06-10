<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Event;
use App\User;
use Illuminate\Support\Facades\Config;

class MemberRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $event;
    public $givenCourses;
    public $password;

    public function __construct(Member $member, Event $event, $givenCourses, $password)
    {
        $this->member = $member;
        $this->event = $event;
        $this->givenCourses = $givenCourses;
        $this->password = $password;
    }

    public function build()
    {
        $from = Config::get("mail.addresses.kamp");
        return $this->from($from->email, $from->name)
            ->to($this->member->email, $this->member->volnaam)
            ->subject("ANDERWIJS - Bevestiging van inschrijving")
            ->view('emails.members.registrationConfirmation');
    }
}
