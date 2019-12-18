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
        return $user->hasCapability("participants::info::show::basic");
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
        return $user->hasCapability("participants::info::show::basic") || $this->ifSelf("participants::info::show::self", $user, $participant);
    }

    /**
     * Determine whether the user can create participants.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCapability("participants::account::create");
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
        return $user->hasCapability("participants::info::edit::basic") || $this->ifSelf("participants::info::edit::self", $user, $participant);
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
        return $user->hasCapability("participants::account::delete");
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
        return $user->hasCapability("participants::account::create");
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
        return $user->hasCapability("participants::account::delete");
    }

    public function showFinance(User $user, Participant $participant)
    {
        return $user->hasCapability("participants::info::show::finance") || $this->ifSelf("participants::info::show::self", $user, $participant);
    }

    public function showPrivate(User $user, Participant $participant)
    {
        return $user->hasCapability("participants::info::show::private") || $this->ifSelf("participants::info::show::self", $user, $participant);
    }

    public function showPractical(User $user, Participant $participant)
    {
        return $user->hasCapability("participants::info::show::practical") || $this->ifSelf("participants::info::show::self", $user, $participant);
    }

    public function showAdministrative(User $user, Participant $participant)
    {
        return $user->hasCapability("participants::info::show::administrative");
    }

    public function changePassword(User $user, $participant)
    {
        return $user->hasCapability("participants::info::edit::administrative") ||  $user->profile == $participant;
    }

    private function ifSelf(string $capability, User $user, Participant $participant): bool
    {
        return $user->hasCapability($capability) && $user->profile_type === "App\Participant" && $user->profile_id === $participant->id;
    }
}
