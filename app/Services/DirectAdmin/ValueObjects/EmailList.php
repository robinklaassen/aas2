<?php

declare(strict_types=1);

namespace App\Services\DirectAdmin\ValueObjects;

final class EmailList
{
    public function __construct(
        private string $name,
        private string $domain,
    ) {
    }

    public function name(): string
    {
        return $this->name;
    }

    public function domain(): string
    {
        return $this->domain;
    }
}
