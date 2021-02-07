<?php

namespace Updater\Services\CommandExecutor;

use Illuminate\Support\Facades\Artisan;
use Updater\Errors\ExecutorException;

class ComposerExecutor implements ExecutorInterface
{
    private string $composer;

    public function __construct(string $composer)
    {
        $this->composer = $composer;
    }

    public function execute(string $cmd, array $args = []): string
    {
        exec($this->composer . ' ' . $cmd . ' ' . implode(' ', $args), $output, $result);

        if ($result !== 0) {
            return $output;
        }

        throw ExecutorException::shellResult($result, $output);
    }
}
