<?php

declare(strict_types=1);

namespace App\Services\DirectAdmin;

use App\Services\DirectAdmin\Contracts\Client;
use App\Services\DirectAdmin\ValueObjects\Command;
use App\Services\DirectAdmin\ValueObjects\EmailList;
use App\Services\DirectAdmin\ValueObjects\EmailListSubscribers;

final class EmailListAdapter implements Contracts\EmailListAdapter
{
    public const CMD = 'CMD_API_EMAIL_LIST';

    public function __construct(
        private Client $client
    ) {
    }

    public function getEmailListSubscribers(EmailList $list): EmailListSubscribers
    {
        $command = Command::create(self::CMD)
            ->asPost([
                'name' => $list->name(),
                'domain' => $list->domain(),
                'action' => 'view',
                'type' => 'list',
            ]);

        $resultString = $this->client->executeQueryString($command);

        $resultObject = [];
        parse_str($resultString, $resultObject);

        $subscribers = [];
        foreach ($resultObject as $key => $email) {
            if ($key[0] !== 's') {
                continue;
            }

            $subscribers[] = $email;
        }

        return new EmailListSubscribers($list, $subscribers);
    }

    public function addEmailListSubscribers(EmailListSubscribers $emailListSubscribers): void
    {
        $command = Command::create(self::CMD)
            ->asPost([
                'name' => $emailListSubscribers->list()->name(),
                'domain' => $emailListSubscribers->list()->domain(),
                'action' => 'add',
                'type' => 'list',
                'email' => implode(',', $emailListSubscribers->subscribers()),
            ]);

        $this->client->executeJson($command);
    }

    public function removeEmailListSubscribers(EmailListSubscribers $emailListSubscribers): void
    {
        $subscribers = [];
        foreach ($emailListSubscribers->subscribers() as $index => $email) {
            $subscribers['select' . $index] = $email;
        }

        $command = Command::create(self::CMD)
            ->asPost(
                array_merge(
                    [
                        'name' => $emailListSubscribers->list()->name(),
                        'domain' => $emailListSubscribers->list()->domain(),
                        'action' => 'delete_subscriber',
                        'type' => 'list',
                    ],
                    $subscribers,
                )
            );

        $this->client->executeJson($command);
    }

    public function setEmailListSubscribers(EmailListSubscribers $emailListSubscribers): void
    {
        $existingSubscribers = $this->getEmailListSubscribers($emailListSubscribers->list());

        $toRemove = array_diff($existingSubscribers->subscribers(), $emailListSubscribers->subscribers());
        $toAdd = array_diff($emailListSubscribers->subscribers(), $existingSubscribers->subscribers());

        $this->removeEmailListSubscribers(new EmailListSubscribers($emailListSubscribers->list(), $toRemove));
        $this->addEmailListSubscribers(new EmailListSubscribers($emailListSubscribers->list(), $toAdd));
    }
}
