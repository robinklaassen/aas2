<?php

namespace App\Mail\internal;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Member;
use App\Event;
use App\Course;

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
        return $this->view('email.internal.coverageChangedNotification')
            ->subject('AAS 2.0 - Vakdekking gewijzigd')
            ->from([Config::get('mail.addresses.aas')])
            ->to([Config::get('mail.addresses.kamp$')]);
    }
}
