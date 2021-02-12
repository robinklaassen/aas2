<?php

namespace Updater\Services\Updater;

interface UpdaterServiceInterface
{
    public function update(): void;
    public function currentVersion(): string;
    public function getUpdateOutput(): array;
}
