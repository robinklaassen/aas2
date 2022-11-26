<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Event;
use Carbon\Carbon;
use Tests\TestCase;

final class EventTest extends TestCase
{
    public function testEarlybirdDiscountDefaultsToFullFactor(): void
    {
        $event = new Event();

        self::assertSame(1.0, $event->earlybirdDiscountFactor);
    }

    public function testEarlybirdDiscountCalculatesFactorWhenActive(): void
    {
        $event = new Event();
        $event->vroegboek_korting_percentage = 5;
        $event->vroegboek_korting_datum_eind = Carbon::tomorrow();

        self::assertSame(0.95, $event->earlybirdDiscountFactor);
    }

    public function testEarlybirdDiscountDefaultsToFullPriceWhenInactive(): void
    {
        $event = new Event();
        $event->vroegboek_korting_percentage = 5;
        $event->vroegboek_korting_datum_eind = Carbon::yesterday();

        self::assertSame(1.0, $event->earlybirdDiscountFactor);
    }
}
