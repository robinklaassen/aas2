<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\EventPackage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPackagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any event packages.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasCapability('event-packages::show');
    }

    /**
     * Determine whether the user can view the event package.
     *
     * @return mixed
     */
    public function view(User $user, EventPackage $eventPackage)
    {
        return $user->hasCapability('event-packages::show');
    }

    /**
     * Determine whether the user can create event packages.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCapability('event-packages::create');
    }

    /**
     * Determine whether the user can update the event package.
     *
     * @return mixed
     */
    public function update(User $user, EventPackage $eventPackage)
    {
        return $user->hasCapability('event-packages::edit');
    }

    /**
     * Determine whether the user can delete the event package.
     *
     * @return mixed
     */
    public function delete(User $user, EventPackage $eventPackage)
    {
        return $user->hasCapability('event-packages::delete');
    }

    /**
     * Determine whether the user can restore the event package.
     *
     * @return mixed
     */
    public function restore(User $user, EventPackage $eventPackage)
    {
        return $user->hasCapability('event-packages::create');
    }

    /**
     * Determine whether the user can permanently delete the event package.
     *
     * @return mixed
     */
    public function forceDelete(User $user, EventPackage $eventPackage)
    {
        return $user->hasCapability('event-packages::delete');
    }
}
