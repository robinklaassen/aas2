<?php

namespace Updater\OutputAggregator;

use Updater\Services\DateTimeProviderInterface;

class InternalOutputRecorder implements OutputRecorderInterface
{
    private $lines = [];
    private DateTimeProviderInterface $dateTimeProvider;

    public function __construct(
        DateTimeProviderInterface $dateTimeProvider
    ) {
        $this->dateTimeProvider = $dateTimeProvider;
    }

    public function record(string $message): void
    {
        $this->lines[] = [
            'datetime' => $this->dateTimeProvider->now(),
            'message' => $message
        ];
    }

    public function getLines(): array
    {
        return $this->lines;
    }
}
