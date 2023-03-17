<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Events\FinishedEvent;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event as EventFacade;
use Tests\TestCase;

final class EventFinalizeTest extends TestCase
{
    use DatabaseTransactions;

    public function testItEmitsFinalizeEvent(): void
    {
        EventFacade::fake();

        $event = new Event();
        $event->finalize();

        EventFacade::assertDispatched(FinishedEvent::class);
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
        EventFacade::fake();

        $originalDate = Carbon::create('2021-10-10');
        $event = new Event();
        $event->finalized_at = $originalDate;

        $event->finalize();

        EventFacade::assertNotDispatched(FinishedEvent::class);
        self::assertSame($originalDate, $event->finalized_at);
    }
}
