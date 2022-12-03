<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Event;
use App\ValueObjects\Pricing\Discount;
use Carbon\Carbon;
use Tests\TestCase;

final class EventTest extends TestCase
{
    public function testEarlybirdDiscountDefaultsToNoDiscount(): void
    {
        $event = new Event();

        self::assertEquals(Discount::none(), $event->earlybirdDiscount);
    }

    public function testEarlybirdDiscountCalculatesDiscountWhenActive(): void
    {
        $event = new Event();
        $event->vroegboek_korting_percentage = 5;
        $event->vroegboek_korting_datum_eind = Carbon::tomorrow();

        self::assertEquals(Discount::fromPercentage(5), $event->earlybirdDiscount);
    }

    public function testEarlybirdDiscountDefaultsToNoDiscountWhenInactive(): void
    {
        $event = new Event();
        $event->vroegboek_korting_percentage = 5;
        $event->vroegboek_korting_datum_eind = Carbon::yesterday();

        self::assertEquals(Discount::none(), $event->earlybirdDiscount);
    }
}
