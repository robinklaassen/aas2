<?php

namespace Updater\Subscribers;

use Updater\Events\PostUpdateEvent;
use Updater\Services\CommandExecutor\ExecutorInterface;

class ComposerPostUpdateSubscriber {

    private ExecutorInterface $composerExecutor;

    public function __construct(ExecutorInterface $composerExecutor)
    {
        $this->composerExecutor = $composerExecutor;
    }

    public function handlePostUpdate()
    {
        $this->composerExecutor->execute('self-update');
        $this->composerExecutor->execute('install');
        $this->composerExecutor->execute('dump-autoload');
    }

    public function subscribe($events)
    {
        $events->listen(
            PostUpdateEvent::class,
            [ComposerPostUpdateSubscriber::class, 'handlePostUpdate']
        );
    }
}
