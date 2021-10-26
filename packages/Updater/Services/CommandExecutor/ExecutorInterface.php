<?php

declare(strict_types=1);

namespace Updater\Services\CommandExecutor;

interface ExecutorInterface
{
    public function execute(string $cmd, array $args = []): string;
}
