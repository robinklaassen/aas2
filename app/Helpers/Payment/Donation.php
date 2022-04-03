<?php

declare(strict_types=1);

namespace App\Helpers\Payment;

final class Donation implements PaymentInterface
{
    private const DESCRIPTION = 'Donatie aan Anderwijs. Bedankt%s!';

    private float $totalAmount;

    private ?string $name;

    public function __construct(float $totalAmount, ?string $name)
    {
        $this->totalAmount = $totalAmount;
        $this->name = $name;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function getDescription(): string
    {
        $name = $this->name !== null ? ' ' . $this->name : '';
        return sprintf(self::DESCRIPTION, $name);
    }

    public function getCurrency(): string
    {
        return 'EUR';
    }

    public function getMetadata()
    {
        return [
            'payment_type' => 'donation',
            'name' => $this->name,
        ];
    }

    public function getRedirectUrl(): string
    {
        return action('iDealController@genericResponse');
    }
}
