<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any locations.
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasCapability('locations::info::basic');
    }

    /**
     * Determine whether the user can view the location.
     *
     * @return mixed
     */
    public function view(User $user, Location $location)
    {
        return $user->hasCapability('locations::info::basic');
    }

    public function viewAdvancedAny(User $user)
    {
        return $user->hasCapability('locations::info::advanced');
    }

    public function viewAdvanced(User $user, Location $location)
    {
        return $this->viewAdvancedAny($user);
    }

    /**
     * Determine whether the user can create locations.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCapability('locations::create');
    }

    /**
     * Determine whether the user can update the location.
     *
     * @return mixed
     */
    public function update(User $user, Location $location)
    {
        return $user->hasCapability('locations::edit::basic');
    }

    public function editAdvancedAny(User $user)
    {
        return $user->hasCapability('locations::edit::advanced');
    }

    public function editAdvanced(User $user, Location $location)
    {
        return $this->editAdvancedAny($user);
    }

    /**
     * Determine whether the user can delete the location.
     *
     * @return mixed
     */
    public function delete(User $user, Location $location)
    {
        return $user->hasCapability('locations::delete');
    }

    /**
     * Determine whether the user can restore the location.
     *
     * @return mixed
     */
    public function restore(User $user, Location $location)
    {
        return $user->hasCapability('locations::create');
    }

    /**
     * Determine whether the user can permanently delete the location.
     *
     * @return mixed
     */
    public function forceDelete(User $user, Location $location)
    {
        return $user->hasCapability('locations::delete');
    }
}
