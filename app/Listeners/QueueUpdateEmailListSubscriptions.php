<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\MemberUpdated;
use App\Jobs\UpdateEmailListSubscriptions;

final class QueueUpdateEmailListSubscriptions
{
    public function handle(MemberUpdated $event)
    {
        if (! $event->member->wasChanged(['email', 'soort'])) {
            return;
        }

        UpdateEmailListSubscriptions::dispatch();
    }
}
