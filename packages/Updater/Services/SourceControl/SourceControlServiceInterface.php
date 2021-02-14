<?php

namespace Updater\Services\SourceControl;

interface SourceControlServiceInterface
{
    public function checkout(string $branch, string $remote = 'origin'): void;
    public function currentVersion(): string;
}
