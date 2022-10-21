<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Webmozart\Assert\Assert;

final class Gender
{
    private const DESCRIPTIONS = [
        'M' => 'Man',
        'V' => 'Vrouw',
        'X' => 'Non-binair',
        'N' => 'Zeg ik liever niet',
    ];

    private function __construct(
        private string $value
    ) {
        Assert::keyExists(self::DESCRIPTIONS, $value);
    }

    public function __toString(): string
    {
        return self::DESCRIPTIONS[$this->value];
    }

    /**
     * @return Gender[]
     */
    public static function All(): \Iterator
    {
        foreach (self::DESCRIPTIONS as $genderValue => $_) {
            yield $genderValue => self::fromString($genderValue);
        }
    }

    public static function Values(): array
    {
        return array_keys(self::DESCRIPTIONS);
    }

    public static function fromString(string $stringValue): self
    {
        return new self($stringValue);
    }
}
