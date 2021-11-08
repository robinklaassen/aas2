<?php

declare(strict_types=1);

namespace App\Services\ActionGenerator;

use App\Services\ActionGenerator\ValueObject\EventActionInput;

interface EventActionApplicator
{
    public function shouldApply(EventActionInput $input): bool;

    public function apply(EventActionInput $input): void;
}
