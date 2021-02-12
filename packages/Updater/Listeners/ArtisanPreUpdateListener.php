<?php


namespace Updater\Listeners;


use Updater\Services\CommandExecutor\ExecutorInterface;

class ArtisanPreUpdateListener {

    private ExecutorInterface $artisanExecutor;

    public function __construct(ExecutorInterface $artisanExecutor)
    {
        $this->artisanExecutor = $artisanExecutor;
    }

    public function handle()
    {
        $this->artisanExecutor->execute('optimize:clear');
    }
}
