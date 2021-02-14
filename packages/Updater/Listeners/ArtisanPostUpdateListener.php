<?php

namespace Updater\Listeners;

use Updater\Services\CommandExecutor\ExecutorInterface;

class ArtisanPostUpdateListener {

    private ExecutorInterface $artisanExecutor;

    public function __construct(ExecutorInterface $artisanExecutor)
    {
        $this->artisanExecutor = $artisanExecutor;
    }

    public function handle()
    {
        $this->artisanExecutor->execute('clear-compiled');
        $this->artisanExecutor->execute('migrate --force');
        $this->artisanExecutor->execute('optimize');
    }
}
