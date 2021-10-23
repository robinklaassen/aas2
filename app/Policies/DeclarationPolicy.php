<?php

namespace App\Policies;

use App\Declaration;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeclarationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $this->viewAll($user) || $this->viewOwn($user);
    }

    public function viewAll(User $user)
    {
        return $user->hasCapability('declarations::show');
    }

    public function process(User $user)
    {
        return $user->hasCapability('declarations::show');
    }

    public function viewOwn(User $user)
    {
        return $user->hasCapability('declarations::self');
    }

    public function view(User $user, Declaration $declaration)
    {
        return $user->hasCapability('declarations::show')
            || (
                $user->hasCapability('declarations::self')
                && $user->isMember()
                && $user->profile->id === $declaration->member_id
            );
    }

    public function create(User $user)
    {
        return $user->hasCapability('declarations::create');
    }

    public function update(User $user, Declaration $declaration)
    {
        return $user->hasCapability('declarations::edit')
            || (
                $user->hasCapability('declarations::self')
                && $user->isMember()
                && $user->profile === $declaration->member
                && !$declaration->isClosed
            );
    }

    public function delete(User $user, Declaration $declaration)
    {
        return $user->hasCapability('declarations::delete')
            || (
                $user->hasCapability('declarations::self')
                && $user->isMember()
                && $user->profile->id === $declaration->member_id
                && !$declaration->isClosed
            );
    }
}
