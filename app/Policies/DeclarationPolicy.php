<?php

namespace App\Policies;

use App\Declaration;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeclarationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any declarations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasCapability('declarations::show');
    }

    /**
     * Determine whether the user can view the declaration.
     *
     * @param  \App\User  $user
     * @param  \App\Declaration  $declaration
     * @return mixed
     */
    public function view(User $user, Declaration $declaration)
    {
        return $user->hasCapability('declarations::show') 
            || (
                $user->hasCapability('declarations::self') 
                && $user->isMember() 
                && $user->member->id === $declaration->member_id
            );
    }

    /**
     * Determine whether the user can create declarations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCapability('declarations::create'); 
    }

    /**
     * Determine whether the user can update the declaration.
     *
     * @param  \App\User  $user
     * @param  \App\Declaration  $declaration
     * @return mixed
     */
    public function update(User $user, Declaration $declaration)
    {
        return $user->hasCapability('declarations::edit') 
            || (
                $user->hasCapability('declarations::self') 
                && $user->isMember() 
                && $user->member->id === $declaration->member_id
                && !$declaration->isClosed
            );
    }

    /**
     * Determine whether the user can delete the declaration.
     *
     * @param  \App\User  $user
     * @param  \App\Declaration  $declaration
     * @return mixed
     */
    public function delete(User $user, Declaration $declaration)
    {
        return $user->hasCapability('declarations::delete') 
            || (
                $user->hasCapability('declarations::self') 
                && $user->isMember() 
                && $user->member->id === $declaration->member_id
                && !$declaration->isClosed
            );
    }

}
