<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\MemberUpdated;
use App\Jobs\UpdateMemberGeolocation;

class QueueMemberGeolocation
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
    public function handle(MemberUpdated $event)
    {
        if ($event->member->wasChanged(['adres', 'postcode', 'plaats'])) {
            UpdateMemberGeolocation::dispatch($event->member);
        }
    }
}
