<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login as LoginEvent;

class SetLastLoginDate
{
    /**
     * Handle the event, sets the user's last_login to current dateTime
     *
     * @param  object  $event
     * @return void
     */
    public function handle(LoginEvent $event)
    {
        $user = $event->user;
        $user->last_login = new \DateTime();
        $user->save();
    }
}
