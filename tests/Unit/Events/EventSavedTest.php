<?php

declare(strict_types=1);

namespace Tests\Unit\Events;

use App\Events\EventSaved;
use App\Models\Event;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

final class EventSavedTest extends MockeryTestCase
{
    /**
     * @dataProvider provideBoolean
     */
    public function testItChecksEventChanges(bool $boolean): void
    {
        $event = Mockery::mock(Event::class);
        $event->expects('wasChanged')
            ->with(EventSaved::PUBLIC_AVAILABLE_FIELDS)
            ->andReturn($boolean);

        self::assertSame($boolean, (new EventSaved($event))->publicDataWasChanged());
    }

    public static function provideBoolean()
    {
        return [
            [true],
            [false],
        ];
    }
}
