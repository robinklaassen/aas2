<?php

namespace Updater\Services;

interface DateTimeProviderInterface
{
    public function now(): \DateTimeImmutable;
}
