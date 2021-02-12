<?php

namespace Updater\Services\CommandExecutor;

use Updater\OutputAggregator\OutputRecorderInterface;

class RecordedExecutor implements ExecutorInterface
{
    private OutputRecorderInterface $outputRecorder;
    private ExecutorInterface $decoratedExecutor;

    public function __construct(ExecutorInterface $decoratedExecutor, OutputRecorderInterface $outputRecorder)
    {
        $this->decoratedExecutor = $decoratedExecutor;
        $this->outputRecorder = $outputRecorder;
    }

    public function execute(string $cmd, array $args = []): string
    {
        $output = $this->decoratedExecutor->execute($cmd, $args);
        $this->outputRecorder->record($output);

        return $output;
    }
}
