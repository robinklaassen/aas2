<?php

declare(strict_types=1);

namespace Tests\Unit\Listeners;

use App\Events\FinishedEvent;
use App\Listeners\AddMemberActionForFinishedEvent;
use App\Models\Event;
use App\Models\Member;
use App\Services\ActionGenerator\EventActionApplicator;
use App\Services\ActionGenerator\ValueObject\EventActionInput;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

final class AddMemberActionForFinishedEventTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    public function testItCallsMultipleApplicators(): void
    {
        /** @var Event $event */
        $event = Event::create();
        /** @var Member $member */
        $member = Member::create();
        $event->members()->save($member);

        $applicator1 = \Mockery::mock(EventActionApplicator::class);
        $applicator1
            ->expects('shouldApply')
            ->withArgs(self::withInputArg($event, $member))
            ->andReturn(true);

        $applicator1
            ->expects('apply')
            ->withArgs(self::withInputArg($event, $member));

        $applicator2 = \Mockery::mock(EventActionApplicator::class);
        $applicator2
            ->expects('shouldApply')
            ->withArgs(self::withInputArg($event, $member))
            ->andReturn(false);

        $subject = new AddMemberActionForFinishedEvent([$applicator1, $applicator2]);

        $subject->handle(new FinishedEvent($event));
    }

    public function testItCallsForMultiplyMembers(): void
    {
        /** @var Event $event */
        $event = Event::create();
        /** @var Member $member */
        $member1 = Member::create();
        $member2 = Member::create();
        $event->members()->saveMany([$member1, $member2]);

        $applicator = \Mockery::mock(EventActionApplicator::class);
        $applicator
            ->expects('shouldApply')
            ->withArgs(self::withInputArg($event, $member1))
            ->andReturn(true);

        $applicator
            ->expects('apply')
            ->withArgs(self::withInputArg($event, $member1));

        $applicator
            ->expects('shouldApply')
            ->withArgs(self::withInputArg($event, $member2))
            ->andReturn(false);

        $subject = new AddMemberActionForFinishedEvent([$applicator]);

        $subject->handle(new FinishedEvent($event));
    }

    private static function withInputArg(Event $event, Member $member): callable
    {
        return static function (EventActionInput $input) use ($member, $event) {
            return $input->getMember()->is($member)
                && $input->getEvent()->is($event);
        };
    }
}
