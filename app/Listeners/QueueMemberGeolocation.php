<?php

namespace App\Listeners;

use App\Events\MemberUpdated;
use App\Jobs\UpdateMemberGeolocation;

class QueueMemberGeolocation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MemberUpdated  $event
     * @return void
     */
    public function handle(MemberUpdated $event)
    {
        UpdateMemberGeolocation::dispatch($event->member);
    }
}
