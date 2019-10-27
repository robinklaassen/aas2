<?php

namespace App\Policies;

use App\Member;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MemberPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any members.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasCapability("members::info::show::basic");
    }

    /**
     * Determine whether the user can view the member.
     *
     * @param  \App\User  $user
     * @param  \App\Member  $member
     * @return mixed
     */
    public function view(User $user, Member $member)
    {
        return ($member->soort == "oud" && $user->hasCapability("members::oud::show"))
            || ($member->soort != "oud" && $user->hasCapability("members::info::show::basic"))
            || ($this->ifSelf("members::info::show::self", $user, $member));
    }

    /**
     * Determine whether the user can create members.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCapability("members::create");
    }

    /**
     * Determine whether the user can update the member.
     *
     * @param  \App\User  $user
     * @param  \App\Member  $member
     * @return mixed
     */
    public function update(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::basic")
            || $this->ifSelf("members::info::edit::self", $user, $member);
    }

    /**
     * Determine whether the user can delete the member.
     *
     * @param  \App\User  $user
     * @param  \App\Member  $member
     * @return mixed
     */
    public function delete(User $user, Member $member)
    {
        return $user->hasCapability("members::delete");
    }

    /**
     * Determine whether the user can restore the member.
     *
     * @param  \App\User  $user
     * @param  \App\Member  $member
     * @return mixed
     */
    public function restore(User $user, Member $member)
    {
        return $user->hasCapability("members::create");
    }

    /**
     * Determine whether the user can permanently delete the member.
     *
     * @param  \App\User  $user
     * @param  \App\Member  $member
     * @return mixed
     */
    public function forceDelete(User $user, Member $member)
    {
        return $user->hasCapability("members::delete");
    }

    public function listOldMembers(User $user)
    {
        return $user->hasCapability("members::old::show");
    }

    public function showFinance(User $user, Member $member)
    {
        return $user->hasCapability("members::info::show::finance") || $user->ifSelf("members::info::show::self", $user, $member);
    }

    public function showPrivate(User $user, Member $member)
    {
        return $user->hasCapability("members::info::show::private") || $user->ifSelf("members::info::show::self", $user, $member);
    }

    public function showPraktical(User $user, Member $member)
    {
        return $user->hasCapability("members::info::show::practical") || $user->ifSelf("members::info::show::self", $user, $member);
    }

    public function showAdministrative(User $user, Member $member)
    {
        return $user->hasCapability("members::info::show::administrative") || $user->ifSelf("members::info::show::self", $user, $member);
    }

    public function showSpecial(User $user, Member $member)
    {
        return $user->hasCapability("members::info::show::administrative");
    }

    public function editBasic(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::basic") || $user->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editFinance(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::finance") || $user->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editPrivate(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::private") || $user->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editPraktical(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::praktical") || $user->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editAdministrative(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::administrative");
    }

    public function editSpecial(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::special");
    }

    private function ifSelf(string $capability, User $user, Member $member): bool
    {
        return $user->hasCapability($capability) && $user->profile == $member;
    }
}
