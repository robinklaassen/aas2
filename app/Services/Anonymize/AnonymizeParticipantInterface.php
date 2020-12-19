<?php

declare(strict_types=1);

namespace App\Services\Anonymize;

use App\Participant;
use DateTimeImmutable;
use Illuminate\Support\Collection;

interface AnonymizeParticipantInterface
{
    /** @return Participant[] */
    public function getParticipantsToAnonymize(DateTimeImmutable $dateTime): Collection;

    public function anonymize(Participant $participant): void;
}
