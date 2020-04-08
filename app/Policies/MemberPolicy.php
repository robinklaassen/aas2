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
            || ($this->ifSelf("members::info::show::self", $user, $member))
            ;
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

    public function showPhone(User $user, Member $member) {
        return $this->showPrivate($user, $member) || $member->hasRole("counselor");
    }

    public function showBirthday(User $user, Member $member) {
        return $this->showPrivate($user, $member) || $member->publish_birthday;
    }

    public function showFinanceAny(User $user)
    {
        return $user->hasCapability("members::info::show::finance");
    }


    public function showFinance(User $user, Member $member)
    {
        return $this->showFinanceAny($user) || $this->ifSelf("members::info::show::self", $user, $member);
    }

    public function showPrivateAny(User $user)
    {
        return $user->hasCapability("members::info::show::private");
    }

    public function showPrivate(User $user, Member $member)
    {
        return $this->showPrivateAny($user) || $this->ifSelf("members::info::show::self", $user, $member);
    }


    public function showPracticalAny(User $user)
    {
        return $user->hasCapability("members::info::show::practical");
    }

    public function showPractical(User $user, Member $member)
    {
        return $this->showPracticalAny($user) || $this->ifSelf("members::info::show::self", $user, $member);
    }

    public function showAdministrativeAny(User $user)
    {
        return $user->hasCapability("members::info::show::administrative");
    }

    public function showAdministrative(User $user, Member $member)
    {
        return $this->showAdministrativeAny($user) || $this->ifSelf("members::info::show::self", $user, $member);
    }

    public function showSpecialAny(User $user)
    {
        return $user->hasCapability("members::info::show::special");
    }

    public function showSpecial(User $user, Member $member)
    {
        return $this->showSpecialAny($user);
    }

    public function editFinanceAny(User $user)
    {
        return $user->hasCapability("members::info::edit::finance");
    }

    public function editFinance(User $user, Member $member)
    {
        return $this->editFinanceAny($user) || $this->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editPrivateAny(User $user)
    {
        return $user->hasCapability("members::info::edit::private");
    }

    public function editPrivate(User $user, Member $member)
    {
        return $this->editPrivateAny($user) || $this->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editPracticalAny(User $user)
    {
        return $user->hasCapability("members::info::edit::practical");
    }

    public function editPractical(User $user, Member $member)
    {
        return $this->editPracticalAny($user) || $this->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editPassword(User $user, Member $member)
    {
        return $user->hasCapability("members::info::edit::password") || $this->ifSelf("members::info::edit::self", $user, $member);
    }

    public function editAdministrativeAny(User $user)
    {
        return $user->hasCapability("members::info::edit::administrative");
    }

    public function editAdministrative(User $user, Member $member)
    {
        return $this->editAdministrativeAny($user);
    }

    public function editSpecialAny(User $user)
    {
        return $user->hasCapability("members::info::edit::special");
    }


    public function editSpecial(User $user, Member $member)
    {
        return $this->editSpecialAny($user);
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