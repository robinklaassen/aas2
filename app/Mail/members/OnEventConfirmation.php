<?php

declare(strict_types=1);

namespace App\Mail\members;

use App\Models\Event;
use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class OnEventConfirmation extends Mailable
{
    use Queueable;

    use SerializesModels;

    public $member;

    public $event;

    public function __construct(Member $member, Event $event)
    {
        $this->member = $member;
        $this->event = $event;
    }

    public function build()
    {
        $subject = sprintf('%s Bevestiging van aanmelding', Config::get('mail.subject_prefix.external'));

        return $this->view('emails.members.onEventConfirmation')
            ->subject($subject)
            ->from([Config::get('mail.addresses.kamp')])
            ->to($this->member->email_anderwijs, $this->member->volnaam);
    }
}
