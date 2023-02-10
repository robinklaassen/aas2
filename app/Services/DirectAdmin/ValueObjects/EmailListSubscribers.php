<?php

declare(strict_types=1);

namespace App\Services\DirectAdmin\ValueObjects;

final class EmailListSubscribers
{
    /**
     * @param string[] $subscribers
     */
    public function __construct(
        private EmailList $list,
        private array $subscribers,
    ) {
    }

    /**
     * @return string[]
     */
    public function subscribers(): array
    {
        return $this->subscribers;
    }

    public function list(): EmailList
    {
        return $this->list;
    }
}
