<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Helpers\Payment\EventPayment;
use App\Helpers\Payment\MolliePaymentProvider;
use App\Event;
use App\Participant;

class PaymentTest extends TestCase
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
     * A basic unit test example.
     *
     * @return void
     */
    public function testEventPaymentMetadata()
    {

        $meta = $this->payment->getMetadata();

        $this->assertEquals($this->participant->id, $meta["participant_id"]);
        $this->assertEquals($this->event->id, $meta["camp_id"]);
        $this->assertEquals("existing", $meta["type"]);

        $this->payment->existing(false);
        $meta = $this->payment->getMetadata();
        $this->assertEquals("new", $meta["type"]);
    }

    public function testEventPaymentKeys()
    {
        $this->assertEquals([$this->participant->id, $this->event->id], $this->payment->getKeys());
    }
    public function testEventPaymentCurrenct()
    {
        $this->assertEquals("EUR", $this->payment->getCurrency());
    }

    public function testEventPaymentDescription()
    {

        $this->assertContains($this->event->code, $this->payment->getDescription());
        $this->assertContains($this->participant->voornaam, $this->payment->getDescription());
        $this->assertContains($this->participant->achternaam, $this->payment->getDescription());
    }

    public function testEventPaymentPrice()
    {
        $this->assertEquals($this->event->prijs, $this->payment->getTotalAmount());
    }
    public function testEventPaymentPriceWithDiscount()
    {
        $partWithDiscount = Participant::findOrFail(2);
        $this->payment->participant($partWithDiscount);
        $this->assertEquals($this->event->prijs * $partWithDiscount->incomeBasedDiscount(), $this->payment->getTotalAmount());
    }
}
