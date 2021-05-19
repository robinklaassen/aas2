<?php

declare(strict_types=1);

namespace App\Services\Anonymize;

interface NameGenerator
{
    public function name(): string;
}
