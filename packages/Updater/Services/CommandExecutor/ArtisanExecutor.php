<?php

declare(strict_types=1);

namespace Updater\Services\CommandExecutor;

use Illuminate\Support\Facades\Artisan;
use Updater\Errors\ExecutorException;

class ArtisanExecutor implements ExecutorInterface
{
    public function execute(string $cmd, array $args = []): string
    {
        $result = Artisan::call($cmd, $args);
        if ($result !== 0) {
            throw ExecutorException::artisanResult($cmd, $result);
        }

        return Artisan::output();
    }
}
