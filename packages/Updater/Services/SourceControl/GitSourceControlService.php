<?php

namespace Updater\Services\SourceControl;

use Updater\Services\CommandExecutor\ExecutorInterface;

class GitSourceControlService implements SourceControlServiceInterface
{
    private ExecutorInterface $executorService;

    public function __construct(ExecutorInterface $shellExecutor)
    {
        $this->executorService = $shellExecutor;
    }

    public function checkout(string $branch = 'master', string $remote = 'origin'): void
    {
        $this->executorService->execute('git fetch', [$remote, $branch]);
        $this->executorService->execute('git reset', ['--hard', $remote . '/' . $branch]);
    }

    public function currentVersion(): string
    {
        return $this->executorService->execute('git log -1 --pretty=format:"[%h] %cd: %s"');
    }
}
