<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\FinishedEvent;
use App\Models\Member;
use App\Services\ActionGenerator\EventActionApplicator;
use App\Services\ActionGenerator\ValueObject\EventActionInput;

final class AddMemberActionForFinishedEvent
{
    /**
     * @var iterable<EventActionApplicator>
     */
    private iterable $applicators;

    /**
     * @param iterable<EventActionApplicator> $applicators
     */
    public function __construct(
        iterable $applicators
    ) {
        $this->applicators = $applicators;
    }

    public function handle(FinishedEvent $finishedEvent)
    {
        $members = $finishedEvent->getEvent()->members;
        /** @var Member $member */
        foreach ($members as $member) {
            $input = new EventActionInput($finishedEvent->getEvent(), $member, (bool) $member->pivot->wissel);
            foreach ($this->applicators as $applicator) {
                if (! $applicator->shouldApply($input)) {
                    continue;
                }

                $applicator->apply($input);
            }
        }
    }
}
