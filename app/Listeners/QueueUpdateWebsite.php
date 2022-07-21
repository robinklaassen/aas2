<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\EventSaved;
use App\Jobs\UpdateWebsite;

final class QueueUpdateWebsite
{
    public function handle(EventSaved $event): void
    {
        if (! $event->publicDataWasChanged()) {
            return;
        }

        UpdateWebsite::dispatch();
    }
}
