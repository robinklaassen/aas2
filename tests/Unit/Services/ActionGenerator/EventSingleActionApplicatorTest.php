<?php

declare(strict_types=1);

namespace Tests\Unit\Services\ActionGenerator;

use App\Services\ActionGenerator\EventSingleActionApplicator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

final class EventSingleActionApplicatorTest extends TestCase
{
    use DatabaseTransactions;

    private EventSingleActionApplicator $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new EventSingleActionApplicator();
    }

    public function testItShouldNotApplyForExistingCode(): void
    {
        $input = EventActionInputFaker::create()
            ->withAction('123', )
            ->withEventData('123')
            ->build();

        self::assertFalse($this->subject->shouldApply($input));
    }

    public function testItShouldNotApplyForCancalledEvent(): void
    {
        $input = EventActionInputFaker::create()
            ->withCancelledEvent()
            ->build();

        self::assertFalse($this->subject->shouldApply($input));
    }

    public function testItShouldApply(): void
    {
        $input = EventActionInputFaker::create()
            ->build();

        self::assertTrue($this->subject->shouldApply($input));
    }

    public function testItAppliesMemberAction(): void
    {
        $input = EventActionInputFaker::create()
            ->withEventData('123', 'test event', '2021-10-11')
            ->build();

        $this->subject->apply($input);

        $this->assertDatabaseHas('actions', [
            'member_id' => $input->getMember()->id,
            'code' => '123',
            'description' => 'test event 2021',
            'points' => 3,
        ]);
    }

    public function testItAppliesWisselAction(): void
    {
        $input = EventActionInputFaker::create()
            ->withEventData('123', 'test event', '2021-10-11')
            ->withWissel()
            ->build();

        $this->subject->apply($input);

        $this->assertDatabaseHas('actions', [
            'member_id' => $input->getMember()->id,
            'code' => '123',
            'description' => 'test event 2021 (w)',
            'points' => 1,
        ]);
    }

    public function testItAppliesTrainingAction(): void
    {
        $input = EventActionInputFaker::create()
            ->withEventData('123', 'test event', '2021-10-11', 'training')
            ->build();

        $this->subject->apply($input);

        $this->assertDatabaseHas('actions', [
            'member_id' => $input->getMember()->id,
            'code' => '123',
            'description' => 'test event 2021',
            'points' => 2,
        ]);
    }
}
