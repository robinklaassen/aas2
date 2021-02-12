<?php

namespace Updater\Services\SourceControl;

use Updater\OutputAggregator\OutputRecorderInterface;
use Updater\Services\CommandExecutor\ExecutorInterface;

class GitSourceControlService implements SourceControlServiceInterface
{
    private ExecutorInterface $shellExecutor;
    private ExecutorInterface $recordedShellExecutor;

    public function __construct(
        ExecutorInterface $shellExecutor,
        ExecutorInterface $recordedShellExecutor
    ) {
        $this->shellExecutor = $shellExecutor;
        $this->recordedShellExecutor = $recordedShellExecutor;
    }

    public function checkout(string $branch = 'master', string $remote = 'origin'): void
    {
        $this->recordedShellExecutor->execute('git fetch', [$remote, $branch]);
        $this->recordedShellExecutor->execute('git reset', ['--hard', $remote . '/' . $branch]);
        $this->recordedShellExecutor->execute('git pull', [$remote, $branch]);

    }

    public function currentVersion(): string
    {
        return $this->shellExecutor->execute('git log -1 --pretty=format:"[%h] %cd: %s"');
    }
}
