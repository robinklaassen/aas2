<?php

namespace Updater\Services\SourceControl;

use Updater\Services\CommandExecutor\ExecutorInterface;

class GitSourceControlService implements SourceControlServiceInterface
{
    private ExecutorInterface $executorService;

    public function __construct(ExecutorInterface $executorService)
    {
        $this->executorService = $executorService;
    }

    public function checkout(string $branch = 'master', string $remote = 'origin'): void
    {
        $this->executorService->execute('git fetch', $branch);
        $this->executorService->execute('git checkout', $branch);
        $this->executorService->execute('git reset', ['--hard', $remote . '/' . $branch]);
    }
}
