<?php

namespace Updater\Subscribers;

use Updater\Events\PostUpdateEvent;
use Updater\Events\PreUpdateEvent;
use Updater\Services\CommandExecutor\ExecutorInterface;

class ArtisanPostUpdateSubscriber {

    private ExecutorInterface $artisanExecutor;

    public function __construct(ExecutorInterface $artisanExecutor)
    {
        $this->artisanExecutor = $artisanExecutor;
    }

    public function handlePostUpdate()
    {
        $this->artisanExecutor->execute('clear-compiled');
        $this->artisanExecutor->execute('migrate --force');
        $this->artisanExecutor->execute('optimize');
    }

    public function subscribe($events)
    {
        $events->listen(
            PostUpdateEvent::class,
            [ArtisanPostUpdateSubscriber::class, 'handlePostUpdate']
        );
    }
}
