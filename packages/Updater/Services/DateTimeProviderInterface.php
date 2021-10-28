<?php

declare(strict_types=1);

namespace Updater\Services;

interface DateTimeProviderInterface
{
    public function now(): \DateTimeImmutable;
}
