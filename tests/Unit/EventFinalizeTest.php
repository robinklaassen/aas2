<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Events\FinishedEvent;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

final class EventFinalizeTest extends TestCase
{
    use DatabaseTransactions;

    public function testItEmitsFinalizeEvent(): void
    {
        $this->expectsEvents([FinishedEvent::class]);
        $event = new Event();

        $event->finalize();
    }

    public function testItSetsFinished(): void
    {
        $event = new Event();
        self::assertNull($event->finalized_at);

        $event->finalize();

        self::assertNotNull($event->finalized_at);
    }

    public function testItDoesntDoAnythingWhenAlreadyFinished(): void
    {
        $this->doesntExpectEvents(FinishedEvent::class);
        $originalDate = Carbon::create('2021-10-10');
        $event = new Event();
        $event->finalized_at = $originalDate;

        $event->finalize();

        self::assertSame($originalDate, $event->finalized_at);
    }
}
