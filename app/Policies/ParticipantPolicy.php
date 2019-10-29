<?php

namespace App\Policies;

use App\Participant;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParticipantPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any participants.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        // todo
        return true;
    }

    /**
     * Determine whether the user can view the participant.
     *
     * @param  \App\User  $user
     * @param  \App\Participant  $participant
     * @return mixed
     */
    public function view(User $user, Participant $participant)
    {
        // todo
        return true;
    }

    /**
     * Determine whether the user can create participants.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        // todo
        return true;
    }

    /**
     * Determine whether the user can update the participant.
     *
     * @param  \App\User  $user
     * @param  \App\Participant  $participant
     * @return mixed
     */
    public function update(User $user, Participant $participant)
    {
        // todo
        return true;
    }

    /**
     * Determine whether the user can delete the participant.
     *
     * @param  \App\User  $user
     * @param  \App\Participant  $participant
     * @return mixed
     */
    public function delete(User $user, Participant $participant)
    {
        // todo
        return true;
    }

    /**
     * Determine whether the user can restore the participant.
     *
     * @param  \App\User  $user
     * @param  \App\Participant  $participant
     * @return mixed
     */
    public function restore(User $user, Participant $participant)
    {
        // todo
        return true;
    }

    /**
     * Determine whether the user can permanently delete the participant.
     *
     * @param  \App\User  $user
     * @param  \App\Participant  $participant
     * @return mixed
     */
    public function forceDelete(User $user, Participant $participant)
    {
        // todo
        return true;
    }
}
