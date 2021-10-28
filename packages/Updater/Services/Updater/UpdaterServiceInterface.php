<?php

declare(strict_types=1);

namespace Updater\Services\Updater;

interface UpdaterServiceInterface
{
    public function update(): void;

    public function currentVersion(): string;

    public function getUpdateOutput(): array;
}
