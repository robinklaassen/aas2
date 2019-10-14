<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login as LoginEvent;

class SetLastLoginDate
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
     * @param  object  $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        $user = $event->user;
        $user->last_login = new \DateTime;
        $user->save();
    }
}
