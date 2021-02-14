<?php

namespace Updater\Services\CommandExecutor;

interface ExecutorInterface
{
    public function execute(string $cmd, array $args = []): string;
}
