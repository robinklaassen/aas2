<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $this->createMember($user) ||
            $this->createParticipant($user) ||
            $user->hasCapability('members::account::update') ||
            $user->hasCapability('members::account::delete') ||
            $user->hasCapability('participants::account::delete');
    }

    public function create(User $user)
    {
        return $this->createMember($user) && $this->createParticipant($user);
    }

    public function createParticipant(User $user)
    {
        return $user->hasCapability('participants::account::create');
    }

    public function createMember(User $user)
    {
        return $user->hasCapability('members::account::create');
    }

    public function changePassword(User $user, User $model)
    {
        return $model->profile_type === "App\Member" && $user->hasCapability('members::info::edit::password') ||
            $model->profile_type === "App\Participant" && $user->hasCapability('participants::info::edit::password');
    }

    public function changeAdmin(User $user, User $model)
    {
        return $model->profile_type === "App\Member" && $user->hasCapability('members::account::update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return $model->profile_type === "App\Member" && $user->hasCapability('members::account::delete') ||
            $model->profile_type === "App\Participant" && $user->hasCapability('participants::account::delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        return $model->profile_type === "App\Member" && $user->hasCapability('members::account::create') ||
            $model->profile_type === "App\Participant" && $user->hasCapability('participants::account::create');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        return $model->profile_type === "App\Member" && $user->hasCapability('members::account::delete') ||
            $model->profile_type === "App\Participant" && $user->hasCapability('participants::account::delete');
    }
}
