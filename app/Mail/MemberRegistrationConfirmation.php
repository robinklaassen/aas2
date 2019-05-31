<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Event;
use App\User;

class RegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $event;
    public $givenCourses;
    public $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Member $member, Event $event, $givenCourses, $password)
    {
        $this->member = $member;
        $this->event = $event;
        $this->givenCourses = $givenCourses;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('kamp@anderwijs.nl', 'Kampcommissie Anderwijs')
            ->subject("ANDERWIJS - Bevestiging van inschrijving")
            ->view('emails.newMemberConfirm')
            ->with([
                "username" => $this->member->user()->username
            ]);
    }
}
