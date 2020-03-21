<?php

namespace App\Policies;

use App\Action;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any actions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasCapability("actions::show");
    }

    /**
     * Determine whether the user can create actions.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCapability("actions::create");
    }

    /**
     * Determine whether the user can update the action.
     *
     * @param  \App\User  $user
     * @param  \App\Action  $action
     * @return mixed
     */
    public function update(User $user, Action $action)
    {
        return $user->hasCapability("actions::edit");
    }

    /**
     * Determine whether the user can delete the action.
     *
     * @param  \App\User  $user
     * @param  \App\Action  $action
     * @return mixed
     */
    public function delete(User $user, Action $action)
    {
        return $user->hasCapability("actions::delete");
    }

    /**
     * Determine whether the user can restore the action.
     *
     * @param  \App\User  $user
     * @param  \App\Action  $action
     * @return mixed
     */
    public function restore(User $user, Action $action)
    {
        return $user->hasCapability("actions::create");
    }

    /**
     * Determine whether the user can permanently delete the action.
     *
     * @param  \App\User  $user
     * @param  \App\Action  $action
     * @return mixed
     */
    public function forceDelete(User $user, Action $action)
    {
        return $user->hasCapability("actions::delete");
    }
}
