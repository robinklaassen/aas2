<?php

namespace Updater\Services\Updater;

use Updater\Events\PostUpdateEvent;
use Updater\Events\PreUpdateEvent;
use Updater\OutputAggregator\OutputRecorderInterface;
use Updater\Services\CommandExecutor\ExecutorInterface;
use Updater\Services\SourceControl\SourceControlServiceInterface;

class UpdaterService implements UpdaterServiceInterface
{
    private SourceControlServiceInterface $controlService;
    private OutputRecorderInterface $outputRecorder;
    private ExecutorInterface $artisanExecutor;
    private string $remote;
    private string $branch;

    public function __construct(
        SourceControlServiceInterface $controlService,
        OutputRecorderInterface $outputRecorder,
        ExecutorInterface $artisanExecutor,
        string $remote,
        string $branch
    ) {
        $this->controlService = $controlService;
        $this->artisanExecutor = $artisanExecutor;
        $this->remote = $remote;
        $this->branch = $branch;
        $this->outputRecorder = $outputRecorder;
    }

    public function update(): void
    {
        $this->preUpdate();
//        $this->controlService->checkout($this->branch, $this->remote);
        $this->postUpdate();
    }

    public function currentVersion(): string
    {
        return $this->controlService->currentVersion();
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

    public function getUpdateOutput(): array
    {
        return array_map(
            fn ($line) => sprintf('[%s]: %s', $line['datetime']->format('c'), $line['message']),
            $this->outputRecorder->getLines()
        );
    }
}
