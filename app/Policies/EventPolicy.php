<?php

namespace App\Policies;

use App\User;
use App\Event;
use Illuminate\Auth\Access\HandlesAuthorization;
use Carbon\Carbon;

class EventPolicy
{
    use HandlesAuthorization;


    public function viewAny(User $user)
    {
        return $user->hasCapability("event::show::basic");
    }

    /**
     * Determine whether the user can view the event.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return mixed
     */
    public function view(User $user, Event $event)
    {
        return $user->hasCapability("event::show::basic") || ($user->hasCapability("event::show::participating") && $event->hasUser($user));
    }

    /**
     * Determine whether the user can create events.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCapability("event::create");
    }

    /**
     * Determine whether the user can update the event.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return mixed
     */
    public function update(User $user, Event $event)
    {
        return $user->hasCapability("event::edit::basic") && $event->datum_einde < Carbon::now();
    }

    /**
     * Determine whether the user can delete the event.
     *
     * @param  \App\User  $user
     * @param  \App\Event  $event
     * @return mixed
     */
    public function delete(User $user, Event $event)
    {
        return $user->hasCapability("event::delete");
    }

    public function viewParticipants(User $user, Event $event)
    {
        return $user->hasCapability("participants::info::show::advanced") || ($user->hasCapability("event::show::participating") && $event->hasUser($user));
    }

    public function viewParticipantsAdvanced(User $user, Event $event)
    {
        return $user->hasCapability("participants::info::show::administrative");
    }

    public function exportParticipants(User $user, Event $event)
    {
        return $user->hasCapability("participants::info::export");
    }

    public function showBasic(User $user, Event $event)
    {
        return $user->hasCapability("event::show::basic") || ($user->hasCapability("event::show::participating") && $event->hasUser($user));
    }

    public function showAdvanced(User $user, Event $event)
    {
        return $user->hasCapability("event::show::advanced");
    }

    public function editBasic(User $user, Event $event)
    {
        return $user->hasCapability("event::edit::basic");
    }

    public function editAdvanced(User $user, Event $event)
    {
        return $user->hasCapability("event::edit::advanced");
    }

    public function editMembers(User $user, Event $event)
    {
        return $user->hasCapability("event::edit::advanced");
    }

    public function subjectCheck(User $user, Event $event)
    {
        return $user->hasCapability("event::subjectcheck");
    }

    public function mailing(User $user, Event $event)
    {
        return $user->hasCapability("event::mailing");
    }

    public function budget(User $user, Event $event)
    {
        return $user->hasCapability("event::budget");
    }

    public function paymentoverview(User $user, Event $event)
    {
        return $user->hasCapability("event::paymentoverview");
    }

    public function placement(User $user, Event $event)
    {
        return $user->hasCapability("event::placement");
    }

    public function nightRegister(User $user, Event $event)
    {
        return $user->hasCapability("event::nightregister");
    }

    public function questionair(User $user, Event $event)
    {
        return $user->isMember() && $event->hasUser($user);
    }

    public function viewReviewResults(User $user, Event $event)
    {
        // TODO: check if the user participated
        return $user->hasCapability("event::show::review");
    }
}
