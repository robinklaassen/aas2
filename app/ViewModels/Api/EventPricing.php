<?php

namespace App\ViewModels\Api;

use App\Event;
use App\Helpers\Payment\EventPayment;
use App\Participant;
use App\ViewModels\ViewModel;

class EventPricing extends ViewModel
{
    const TYPE_UNKNOWN = 'UNKNOWN';
    const TYPE_KNOWN = 'KNOWN';
    const TYPE_NONE = 'NONE';

    protected array $visible = ['type', 'full', 'discounts'];

    protected string $type;
    protected ?float $fullPrice;

    private function __construct(string $type, ?float $fullPrice)
    {
        $this->type = $type;
        $this->fullPrice = $fullPrice;
    }

    public static function fromEvent(Event $event): self
    {
        $price = $event->prijs;
        $type = self::TYPE_KNOWN;

        if ($price === 0) {
            $type = self::TYPE_NONE;
        }
        if ($price === null) {
            $type = self::TYPE_UNKNOWN;
        }

        return new self($type, $event->prijs);
    }

    public function getFull(): ?float
    {
        return $this->fullPrice;
    }

    public function getDiscounts(): array
    {
        if ($this->type !== self::TYPE_KNOWN) {
            return [];
        }

        $discountsWithPrice = [];
        foreach (Participant::INCOME_DISCOUNT_TABLE as $priceFraction) {
            $discountPercentage = $this->priceFractionToDiscountPercentage($priceFraction);
            $discountsWithPrice[$discountPercentage] = EventPayment::calculate_price($this->fullPrice, $priceFraction);
        }

        return $discountsWithPrice;
    }

    public function getType(): string
    {
        return $this->type;
    }

    private function priceFractionToDiscountPercentage(float $priceFraction): string
    {
        return ((1.0 - $priceFraction) * 100.0) . '%';
    }
}
