<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers\Payment;

use App\Helpers\Payment\Donation;
use App\Helpers\Payment\PaymentInterface;
use App\Http\Controllers\DonateController;
use Tests\TestCase;

class DonationTest extends TestCase
{
    private const AMOUNT = 42.69;

    private const NAME = ':name:';

    private PaymentInterface $payment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->payment = new Donation(self::AMOUNT, self::NAME);
    }

    public function testDonationMetadata()
    {
        self::assertSame([
            'payment_type' => 'donation',
            'name' => self::NAME,
        ], $this->payment->getMetadata());
    }

    public function testRedirectUrl()
    {
        self::assertSame(
            action([DonateController::class, 'response']),
            $this->payment->getRedirectUrl()
        );
    }

    public function testDonationCurrency()
    {
        self::assertSame('EUR', $this->payment->getCurrency());
    }

    public function testDonationDescription()
    {
        self::assertStringContainsString(self::NAME, $this->payment->getDescription());
        self::assertStringContainsString('Anderwijs', $this->payment->getDescription());
        self::assertStringContainsString('Bedankt', $this->payment->getDescription());
    }

    public function testDonationPrice()
    {
        self::assertSame(self::AMOUNT, $this->payment->getTotalAmount());
    }

    public function testNamelessDonation()
    {
        $donation = new Donation(self::AMOUNT, null);

        self::assertSame('Donatie aan Anderwijs. Bedankt!', $donation->getDescription());
    }
}
