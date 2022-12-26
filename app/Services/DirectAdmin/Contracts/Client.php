<?php

declare(strict_types=1);

namespace App\Services\DirectAdmin\Contracts;

use App\Services\DirectAdmin\ValueObjects\Command;

interface Client
{
    public function executeQueryString(Command $command): string;

    public function executeJson(Command $command): array;
}
