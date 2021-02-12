<?php

namespace Updater\Services;

class DateTimeProvider implements DateTimeProviderInterface
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
