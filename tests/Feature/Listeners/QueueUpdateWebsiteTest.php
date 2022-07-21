<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners;

use App\Events\EventSaved;
use App\Jobs\UpdateWebsite;
use App\Listeners\QueueUpdateWebsite;
use App\Models\Event;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

final class QueueUpdateWebsiteTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testItDispatchesUpdateWebsite(): void
    {
        $event = Mockery::mock(Event::class);
        $event->expects('wasChanged')->andReturn(true);

        Queue::fake();

        (new QueueUpdateWebsite())->handle(new EventSaved($event));

        Queue::assertPushed(UpdateWebsite::class);
    }

    public function testItDoesNotDispatchWhenNothingChanges(): void
    {
        $event = Mockery::mock(Event::class);
        $event->expects('wasChanged')->andReturn(false);

        Queue::fake();

        (new QueueUpdateWebsite())->handle(new EventSaved($event));

        Queue::assertNothingPushed();
    }
}
