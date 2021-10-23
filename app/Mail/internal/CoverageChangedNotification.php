<?php

namespace App\Mail\internal;

use App\Course;
use App\Event;
use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class CoverageChangedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $member;
    public $event;
    public $course;
    public $courseLevelFrom;
    public $courseLevelTo;
    public $statusAfter;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        Member $member,
        Event $event,
        Course $course,
        $courseLevelFrom,
        $courseLevelTo,
        $statusAfter
    ) {
        $this->member = $member;
        $this->event = $event;
        $this->course = $course;
        $this->courseLevelFrom = $courseLevelFrom;
        $this->courseLevelTo = $courseLevelTo;
        $this->statusAfter = $statusAfter;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = sprintf('%s Vakdekking gewijzigd', Config::get('mail.subject_prefix.internal'));
        
        return $this->view('emails.internal.coverageChangedNotification')
            ->subject($subject)
            ->from([Config::get('mail.addresses.aas')])
            ->to([Config::get('mail.addresses.kamp')]);
    }
}
