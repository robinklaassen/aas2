<?php

namespace Updater\Services\CommandExecutor;

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
