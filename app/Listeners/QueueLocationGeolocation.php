<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\LocationUpdated;
use App\Jobs\UpdateLocationGeolocation;

class QueueLocationGeolocation
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LocationUpdated $event)
    {
        if ($event->location->wasChanged(['adres', 'postcode', 'plaats'])) {
            UpdateLocationGeolocation::dispatch($event->location);
        }
    }
}
