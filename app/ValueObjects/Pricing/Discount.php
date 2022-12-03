<?php

declare(strict_types=1);

namespace App\ValueObjects\Pricing;

final class Discount
{
    private function __construct(
        private int $percentage,
    ) {
    }

    public function __toString(): string
    {
        return sprintf('%d%%', $this->percentage);
    }

    public static function none(): self
    {
        return new self(0);
    }

    public static function fromPercentage(int $percentage): self
    {
        return new static($percentage);
    }

    public static function max(self ...$discounts): self
    {
        $maxDiscount = max(...array_map(static fn (Discount $discount) => $discount->percentage, $discounts));

        return self::fromPercentage($maxDiscount);
    }

    public function asFactor(): float
    {
        return 1.0 - ($this->percentage / 100.0);
    }
}
