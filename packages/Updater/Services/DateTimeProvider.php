<?php

declare(strict_types=1);

namespace Updater\Services;

class DateTimeProvider implements DateTimeProviderInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
