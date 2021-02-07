<?php

namespace Updater\Services\Updater;

use Updater\Events\PostUpdateEvent;
use Updater\Events\PreUpdateEvent;
use Updater\Services\CommandExecutor\ExecutorInterface;
use Updater\Services\SourceControl\SourceControlServiceInterface;

class UpdaterService implements UpdaterServiceInterface
{
    private SourceControlServiceInterface $controlService;
    private ExecutorInterface $artisanExecutor;
    private string $remote;
    private string $branch;

    public function __construct(
        SourceControlServiceInterface $controlService,
        ExecutorInterface $artisanExecutor,
        string $remote,
        string $branch
    ) {
        $this->controlService = $controlService;
        $this->artisanExecutor = $artisanExecutor;
        $this->remote = $remote;
        $this->branch = $branch;
    }

    public function update(): void
    {
        $this->preUpdate();
        $this->controlService->checkout($this->branch, $this->remote);
        $this->postUpdate();
    }

    protected function preUpdate()
    {
        $this->artisanExecutor->execute('down');
        PreUpdateEvent::dispatch();
    }

    protected function postUpdate()
    {
        PostUpdateEvent::dispatch();
        $this->artisanExecutor->execute('up');
    }
}
