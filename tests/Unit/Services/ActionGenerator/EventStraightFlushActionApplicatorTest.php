<?php

declare(strict_types=1);

namespace Tests\Unit\Services\ActionGenerator;

use App\Models\Event;
use App\Models\Member;
use App\Services\ActionGenerator\EventStraightFlushActionApplicator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

final class EventStraightFlushActionApplicatorTest extends TestCase
{
    use DatabaseTransactions;

    private EventStraightFlushActionApplicator $subject;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $this->subject = new EventStraightFlushActionApplicator();
    }

    public function testItShouldNotApplyCancelledEvent(): void
    {
        $input = EventActionInputFaker::create()
            ->withCancelledEvent()
            ->build();

        self::assertFalse($this->subject->shouldApply($input));
    }

    public function testItShouldNotApplyWithExistingAction(): void
    {
        $input = EventActionInputFaker::create()
            ->withAction('123')
            ->withEventData('123')
            ->build();

        self::assertFalse($this->subject->shouldApply($input));
    }

    /**
     * @dataProvider eventCodesDataProvider
     */
    public function testItShouldApplyWithEvents(array $eventCodes, bool $expected): void
    {
        $input = EventActionInputFaker::create()
            ->withEventData('K2122', 'test', '2021-11-12')
            ->build();

        $this->attachNewEventsWithCodes($input->getMember(), $eventCodes);

        self::assertSame($expected, $this->subject->shouldApply($input));
    }

    public static function eventCodesDataProvider(): \Generator
    {
        yield 'should apply' => [
            ['V1920', 'K1920', 'Z2021', 'H2021', 'M2021'], true,
        ];
        yield 'should not apply with to less' => [
            ['V1920', 'K1920', 'Z2021', 'H2021'], false,
        ];
        yield 'should not apply with duplicates' => [
            ['V1920', 'K1920', 'Z2021', 'Z1920', 'H2021'], false,
        ];
        yield 'should not apply; reduces K and N eventcodes to the same' => [
            ['V1920', 'K1920', 'Z2021', 'N1819', 'H2021'], false,
        ];
    }

    public function testItApplyAddsStraightFlush(): void
    {
        $input = EventActionInputFaker::create()
            ->build();

        $this->subject->apply($input);

        $this->assertDatabaseHas('actions', [
            'member_id' => $input->getMember()->id,
            'description' => 'Straight flush',
            'code' => 'straight_flush',
            'points' => 3,
        ]);
    }

    private function attachNewEventsWithCodes(Member $member, array $eventCodes): void
    {
        $events = array_map(
            fn (string $code) => Event::create([
                'code' => $code,
                'datum_start' => '2020-09-08',
            ]),
            $eventCodes
        );

        $member->events()->attach(
            array_map(fn (Event $event) => $event->id, $events),
            [
                'wissel' => 0,
            ]
        );

        $member->events()->attach(
            array_map(fn (Event $event) => $event->id, $events),
            [
                'wissel' => 0,
            ]
        );
    }
}
