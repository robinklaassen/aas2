<?php

namespace Updater\Services\CommandExecutor;

use Illuminate\Support\Facades\Artisan;
use Updater\Errors\ExecutorException;
use Updater\OutputAggregator\OutputRecorderInterface;
use Updater\Services\DateTimeProviderInterface;

class ComposerExecutor extends ShellExecutor
{
    private string $composer;

    public function __construct(string $composer)
    {
        $this->composer = $composer;
    }

    public function execute(string $cmd, array $args = []): string
    {
        return parent::execute($this->composer . ' ' . $cmd, $args);
    }
}
