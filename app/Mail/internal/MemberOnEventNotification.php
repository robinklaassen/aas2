<?php

declare(strict_types=1);

namespace App\Mail\internal;

use App\Models\Event;
use App\Models\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class MemberOnEventNotification extends Mailable
{
    use Queueable;

    use SerializesModels;

    public $event;

    public $member;

    public function __construct(Member $member, Event $event)
    {
        $this->member = $member;
        $this->event = $event;
    }

    public function build()
    {
        $subject = sprintf('%s Lid op kamp', Config::get('mail.subject_prefix.internal'));

        return $this->view('emails.internal.memberOnEventNotification')
            ->from([Config::get('mail.addresses.aas')])
            ->to([Config::get('mail.addresses.kamp')])
            ->subject($subject);
    }
}
