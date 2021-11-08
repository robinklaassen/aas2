<?php

declare(strict_types=1);

namespace App\Services\ActionGenerator\ValueObject;

use App\Models\Event;
use App\Models\Member;

final class EventActionInput
{
    private Event $event;

    private Member $member;

    private bool $wissel;

    public function __construct(
        Event $event,
        Member $member,
        bool $wissel
    ) {
        $this->event = $event;
        $this->member = $member;
        $this->wissel = $wissel;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    public function getMember(): Member
    {
        return $this->member;
    }

    public function isWissel(): bool
    {
        return $this->wissel;
    }
}
