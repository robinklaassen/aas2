<?php

declare(strict_types=1);

namespace App\Policies;

use App\Event;
use App\Member;
use App\Participant;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasCapability('event::show::basic');
    }

    /**
     * Determine whether the user can view the event.
     *
     * @return mixed
     */
    public function view(User $user, Event $event)
    {
        return $user->hasCapability('event::show::basic') || ($user->hasCapability('event::show::participating') && $event->hasUser($user));
    }

    /**
     * Determine whether the user can create events.
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasCapability('event::create');
    }

    /**
     * Determine whether the user can update the event.
     *
     * @return mixed
     */
    public function update(User $user, Event $event)
    {
        return $user->hasCapability('event::edit::basic') && $event->datum_einde < Carbon::now();
    }

    /**
     * Determine whether the user can delete the event.
     *
     * @return mixed
     */
    public function delete(User $user, Event $event)
    {
        return $user->hasCapability('event::delete');
    }

    public function showBasicAny(User $user)
    {
        return $user->hasCapability('event::show::basic');
    }

    public function showBasic(User $user, Event $event)
    {
        return $this->showBasicAny($user) || ($user->hasCapability('event::show::participating') && $event->hasUser($user));
    }

    public function showAdvancedAny(User $user)
    {
        return $user->hasCapability('event::show::advanced');
    }

    public function showAdvanced(User $user, Event $event)
    {
        return $this->showAdvancedAny($user);
    }

    public function editAdvancedAny(User $user)
    {
        return $user->hasCapability('event::edit::advanced');
    }

    public function editAdvanced(User $user, Event $event)
    {
        return $this->editAdvancedAny($user);
    }

    public function addMember(User $user)
    {
        return $user->hasCapability('event::members::add');
    }

    public function editMember(User $user, Event $event, Member $member)
    {
        return $user->hasCapability('event::members::edit') || ($member->isUser($user) && $user->hasCapability('members::info::edit::self'));
    }

    public function removeMember(User $user, Event $event, Member $member)
    {
        return $user->hasCapability('event::members::remove');
    }

    public function viewParticipants(User $user, Event $event)
    {
        return $this->showAdvanced($user, $event) || ($user->hasCapability('event::show::participating') && $event->hasUser($user));
    }

    public function viewParticipantsAdvanced(User $user, Event $event)
    {
        return $this->viewParticipants($user, $event) && $user->hasCapability('participants::info::show::administrative');
    }

    public function exportParticipants(User $user, Event $event)
    {
        return $user->hasCapability('participants::info::export');
    }

    /**
     * Function is not bound to an event instance because it was invoked on the participantPolicy before an event is selected
     */
    public function addParticipant(User $user)
    {
        return $user->hasCapability('event::participants::add');
    }

    public function editParticipant(User $user, Event $event, Participant $participant)
    {
        return $user->hasCapability('event::participants::edit') || ($event->datum_start->gt(Carbon::now()) && $participant->isUser($user) && $user->hasCapability('participants::info::edit::self'));
    }

    public function removeParticipant(User $user, Event $event, Participant $participant)
    {
        return $user->hasCapability('event::participants::remove');
    }

    public function subjectCheck(User $user, Event $event)
    {
        return $user->hasCapability('event::subjectcheck');
    }

    public function mailing(User $user, Event $event)
    {
        return $user->hasCapability('event::mailing');
    }

    public function budget(User $user, Event $event)
    {
        return $user->hasCapability('event::budget');
    }

    public function paymentoverview(User $user, Event $event)
    {
        return $user->hasCapability('event::paymentoverview');
    }

    public function placement(User $user, Event $event)
    {
        return $user->hasCapability('event::placement');
    }

    public function nightRegister(User $user, Event $event)
    {
        return $user->hasCapability('event::nightregister');
    }

    public function questionair(User $user, Event $event)
    {
        return $user->isMember() && $event->hasUser($user);
    }

    public function viewReviewResults(User $user, Event $event)
    {
        // TODO: check if the user participated
        return $user->hasCapability('event::show::review');
    }

    public function cancel(User $user, Event $event)
    {
        return $this->editAdvanced($user, $event);
    }
}
