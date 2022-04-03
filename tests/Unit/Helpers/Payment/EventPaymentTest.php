<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers\Payment;

use App\Helpers\Payment\EventPayment;
use App\Models\Event;
use App\Models\Participant;
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

    public function testEventPaymentRedirectUrl()
    {
        $this->assertSame(
            action('iDealController@eventPaymentResponse', [$this->participant->id, $this->event->id]),
            $this->payment->getRedirectUrl()
        );
    }

    public function testEventPaymentCurrenct()
    {
        $this->assertSame('EUR', $this->payment->getCurrency());
    }

    public function testEventPaymentDescription()
    {
        $this->assertStringContainsString($this->event->code, $this->payment->getDescription());
        $this->assertStringContainsString($this->participant->voornaam, $this->payment->getDescription());
        $this->assertStringContainsString($this->participant->achternaam, $this->payment->getDescription());
    }

    public function testEventPaymentPrice()
    {
        $this->assertSame((float) $this->event->prijs, $this->payment->getTotalAmount());
    }

    public function testEventPaymentPriceWithIncomeBasedDiscount()
    {
        $partWithDiscount = Participant::findOrFail(2);
        $this->payment->participant($partWithDiscount);
        $this->assertSame($this->event->prijs * $partWithDiscount->incomeBasedDiscount, $this->payment->getTotalAmount());
    }
}
