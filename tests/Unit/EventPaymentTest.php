<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Event;
use App\Helpers\Payment\EventPayment;
use App\Participant;
use Tests\TestCase;

class EventPaymentTest extends TestCase
{
    private $payment;

    private $event;

    private $participant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->event = Event::findOrFail(1);
        $this->participant = Participant::findOrFail(1);

        $this->payment = (new EventPayment())
            ->event($this->event)
            ->participant($this->participant)
            ->existing(true);
    }

    /**
     * Tests if the metadata call
     */
    public function testEventPaymentMetadata()
    {
        $meta = $this->payment->getMetadata();

        $this->assertSame($this->participant->id, $meta['participant_id']);
        $this->assertSame($this->event->id, $meta['camp_id']);
        $this->assertSame('existing', $meta['type']);

        $this->payment->existing(false);
        $meta = $this->payment->getMetadata();
        $this->assertSame('new', $meta['type']);
    }

    /**
     * Tests if the keys call
     */
    public function testEventPaymentKeys()
    {
        $this->assertSame([$this->participant->id, $this->event->id], $this->payment->getKeys());
    }

    /**
     * Tests the currency
     */
    public function testEventPaymentCurrenct()
    {
        $this->assertSame('EUR', $this->payment->getCurrency());
    }

    /**
     * Tests the description
     */
    public function testEventPaymentDescription()
    {
        $this->assertStringContainsString($this->event->code, $this->payment->getDescription());
        $this->assertStringContainsString($this->participant->voornaam, $this->payment->getDescription());
        $this->assertStringContainsString($this->participant->achternaam, $this->payment->getDescription());
    }

    /**
     * Tests the totalAmount call
     */
    public function testEventPaymentPrice()
    {
        $this->assertSame((float) $this->event->prijs, $this->payment->getTotalAmount());
    }

    /**
     * Tests the totalAmount call with an income based discount
     */
    public function testEventPaymentPriceWithDiscount()
    {
        $partWithDiscount = Participant::findOrFail(2);
        $this->payment->participant($partWithDiscount);
        $this->assertSame($this->event->prijs * $partWithDiscount->incomeBasedDiscount, $this->payment->getTotalAmount());
    }
}
