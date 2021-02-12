<?php

namespace Updater\OutputAggregator;

interface OutputRecorderInterface
{
    public function record(string $line);
    public function getLines(): array;
}
