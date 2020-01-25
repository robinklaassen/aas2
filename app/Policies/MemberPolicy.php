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
        return $user->hasCapability("members::account::create");
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
        return $user->hasCapability("members::account::delete");
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
        return $user->hasCapability("members::account::create");
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
        return $user->hasCapability("members::account::delete");
    }

    public function listOldMembers(User $user)
    {
        return $user->hasCapability("members::old::show");
    }

    public function showFinanceAny(User $user)
    {
        return $user->hasCapability("members::info::show::finance");
    }


    public function showFinance(User $user, Member $member)
    {
        return $this->showFinanceAny($user) || $this->ifSelf("members::info::show::self", $user, $member);
    }

    public function showPrivate(User $user, Member $member)
    {
        return $this->showPrivateAny($user) || $this->ifSelf("members::info::show::self", $user, $member);
    }

    public function showPrivateAny(User $user)
    {
        return $user->hasCapability("members::info::show::private");
    }

    public function showPractical(User $user, Member $member)
    {
        return $this->showAnyPractical($user) || $this->ifSelf("members::info::show::self", $user, $member);
    }

    public function showAnyPractical(User $user)
    {
        return $user->hasCapability("members::info::show::practical");
    }

    public function showAdministrative(User $user, Member $member)
    {
        return $this->showAnyAdministrative($user) || $this->ifSelf("members::info::show::self", $user, $member);
    }

    public function showAnyAdministrative(User $user)
    {
        return $user->hasCapability("members::info::show::administrative");
    }

    public function showAnySpecial(User $user)
    {
        return $user->hasCapability("members::info::show::special");
    }

    public function showSpecial(User $user, Member $member)
    {
        return $this->showAnySpecial($user);
    }

    public function editFinance(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::finance") || $this->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editPrivate(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::private") || $this->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editPractical(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::practical") || $this->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editPassword(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::password") || $this->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editAnyAdministrative(User $user)
    {
        return $user->hasCapability("members::info::edit::administrative");
    }

    public function editAdministrative(User $user, Member $member)
    {
        return $this->editAnyAdministrative($user);
    }

    public function editSpecial(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::special");
    }

    public function onEvent(User $user, Member $member)
    {
        return $user->can("addMember", \App\Event::class) ||  $this->ifSelf("members::info::edit::self", $user, $member);
    }

    private function ifSelf(string $capability, User $user, Member $member): bool
    {
        return $user->hasCapability($capability) && $user->profile_type === "App\Member" && $user->profile_id === $member->id;
    }
}
