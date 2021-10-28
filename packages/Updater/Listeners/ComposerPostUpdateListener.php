<?php

declare(strict_types=1);

namespace Updater\Listeners;

use Updater\Services\CommandExecutor\ExecutorInterface;

class ComposerPostUpdateListener
{
    private ExecutorInterface $composerExecutor;

    public function __construct(ExecutorInterface $composerExecutor)
    {
        $this->composerExecutor = $composerExecutor;
    }

    public function handle()
    {
        $this->composerExecutor->execute('install');
        $this->composerExecutor->execute('dump-autoload');
    }
}
