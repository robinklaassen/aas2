<?php

declare(strict_types=1);

namespace App\Services\Anonymize;

interface NameGeneratorInterface
{
    public function name(): string;
}
