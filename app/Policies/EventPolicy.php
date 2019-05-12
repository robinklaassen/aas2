<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Event;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // public function viewParticipants(User $user, Event $event)
    // {
    //     return $user->capabilities()->contains("view-event-participants") || ($user->isMember() && $user->profile()->events()->contains($event));
    // }

    // public function create(User $user)
    // {
    //     return $user->capabilities()->contains("create-event");
    // }

    // public function update(User $user, Event $event)
    // {
    //     return $user->capabilities()->contains("edit-event") && $event->datum_einde < Carbon::now();
    // }

    public function exportParticipants(User $user, Event $event)
    {
        return $user->capabilities()->contains("export-event-participants");
    }
}
