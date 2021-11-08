<?php

declare(strict_types=1);

namespace App\Mail\members;

use App\Models\Event;
use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class MemberRegistrationConfirmation extends Mailable
{
    use Queueable;

    use SerializesModels;

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
        $subject = sprintf('%s Bevestiging van inschrijving', Config::get('mail.subject_prefix.external'));

        return $this->from([Config::get('mail.addresses.kamp')])
            ->to($this->member->email, $this->member->volnaam)
            ->subject($subject)
            ->view('emails.members.registrationConfirmation');
    }
}
