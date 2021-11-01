<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Facades\Mollie;
use App\Helpers\Payment\PaymentInterface;
use App\Models\Event;
use App\Models\Participant;
use Mockery;
use Tests\TestCase;

class TestPaymentPayment implements PaymentInterface
{
    public function getTotalAmount(): float
    {
        return 123.45;
    }

    public function getDescription(): string
    {
        return 'TestDescription';
    }

    public function getCurrency(): string
    {
        return 'EUR';
    }

    public function getMetadata()
    {
        return [
            'id' => 42,
        ];
    }

    public function getKeys(): array
    {
        return ['test', 42];
    }
}

class MolliePaymentProviderTest extends TestCase
{
    private $payment;

    private $event;

    private $participant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->event = Event::findOrFail(1);
        $this->participant = Participant::findOrFail(1);

        $this->payment = (new TestPaymentPayment());
    }

    /**
     * Processes a fake payment and checks if the correct data is transfered to the mollie api
     */
    public function testPaymentProcessing()
    {
        $mock = Mollie::fakePayments();

        $mock->shouldReceive('create')->once()->with(Mockery::on(function ($arg) {
            $formattedAmount = number_format($this->payment->getTotalAmount(), 2, '.', '');
            return $arg['amount']['currency'] === 'EUR'
                && $arg['amount']['value'] === $formattedAmount
                && $arg['description'] === $this->payment->getDescription()
                && $arg['metadata'] === $this->payment->getMetadata()
                && $arg['webhookUrl'] === url('iDeal-webhook')
                && $arg['redirectUrl'] === url('iDeal-response/test/42')
                && $arg['method'] === \Mollie\Api\Types\PaymentMethod::IDEAL;
        }))->andReturns(new class() {
            public function getCheckoutUrl()
            {
                return 'testUrl';
            }
        });

        Mollie::process($this->payment);
    }
}
