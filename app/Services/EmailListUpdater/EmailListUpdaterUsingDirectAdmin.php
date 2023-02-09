<?php

declare(strict_types=1);

namespace App\Services\EmailListUpdater;

use App\Models\Member;
use App\Services\DirectAdmin\Contracts\EmailListAdapter;
use App\Services\DirectAdmin\ValueObjects\EmailList;
use App\Services\DirectAdmin\ValueObjects\EmailListSubscribers;

final class EmailListUpdaterUsingDirectAdmin implements EmailListUpdater
{
    public function __construct(
        private EmailList $list,
        private EmailListAdapter $emailListAdapter,
        private array $subscriberMemberTypes
    ) {
    }

    public function update(): void
    {
        $emails = Member::query()
            ->whereIn('soort', $this->subscriberMemberTypes)
            ->pluck('email')
            ->toArray();

        $subscribers = new EmailListSubscribers(
            $this->list,
            $emails,
        );

        $this->emailListAdapter->setEmailListSubscribers($subscribers);
    }
}
