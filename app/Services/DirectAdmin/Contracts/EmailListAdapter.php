<?php

declare(strict_types=1);

namespace App\Services\DirectAdmin\Contracts;

use App\Services\DirectAdmin\ValueObjects\EmailList;
use App\Services\DirectAdmin\ValueObjects\EmailListSubscribers;

interface EmailListAdapter
{
    public function getEmailListSubscribers(EmailList $list): EmailListSubscribers;

    public function setEmailListSubscribers(EmailListSubscribers $emailListSubscribers): void;

    public function addEmailListSubscribers(EmailListSubscribers $emailListSubscribers): void;

    public function removeEmailListSubscribers(EmailListSubscribers $emailListSubscribers): void;
}
