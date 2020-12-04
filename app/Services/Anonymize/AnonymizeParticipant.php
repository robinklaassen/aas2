<?php

declare(strict_types=1);

namespace App\Services\Anonymize;

use App\Participant;
use App\Services\ObjectManager\ObjectManagerInterface;
use Carbon\Carbon;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AnonymizeParticipant implements AnonymizeParticipantInterface
{
    const YEAR_NO_CAMP = 8;
    /**
     * @var AnonymizeGeneratorInterface
     */
    private $generator;
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    public function __construct(
        AnonymizeGeneratorInterface $generator,
        ObjectManagerInterface $objectManager
    ) {
        $this->generator = $generator;
        $this->objectManager = $objectManager;
    }

    /** @return Collection<Participant> */
    public function getParticipantsToAnonymize(DateTimeImmutable $currentTime): Collection
    {
        $inactiveBoundary = (new Carbon($currentTime))->subYears(self::YEAR_NO_CAMP);

        return Participant::query()
            ->leftJoin('event_participant', 'participants.id', '=', 'event_participant.participant_id')
            ->leftJoin('events', 'events.id', '=', 'event_participant.event_id')
            ->whereNull('participants.anonymized_at')
            ->where(function ($query) use ($inactiveBoundary) {
                $query
                    ->where('events.datum_eind', '<=', $inactiveBoundary)
                    ->orWhere(function ($query) use ($inactiveBoundary) {
                        /** @var Builder $query */
                        $query->whereNull('event_participant.event_id')
                            ->where('participants.created_at', '<=', $inactiveBoundary);
                    });
            })
            ->distinct()
            ->select('participants.*')
            ->get();
    }

    public function anonymize(Participant $participant): void
    {
        $participant->voornaam = $this->generator->firstname();
        $participant->achternaam = $this->generator->surname();
        $participant->tussenvoegsel = $this->generator->surnamePrefix();
        // keep geslacht
        $participant->geboortedatum = $this->generator->birthdate();
        $participant->adres = $this->generator->address();
        $participant->postcode = $this->generator->zipcode();
        $participant->plaats = $this->generator->city();
        $participant->telefoon_deelnemer = $this->generator->telephone();
        $participant->telefoon_ouder_vast = $this->generator->telephone();
        $participant->telefoon_ouder_mobiel = $this->generator->telephone();
        $participant->email_deelnemer = $this->generator->email();
        $participant->email_ouder = $this->generator->email();
        $participant->mag_gemaild = false;
        $participant->inkomen = 0;
        $participant->inkomensverklaring = false;
        $participant->school = "";
        $participant->niveau = "VMBO";
        $participant->klas = 0;
        $participant->hoebij = "";
        $participant->opmerkingen = "Geänonimiseerd";
        $participant->anonymized_at = new DateTimeImmutable();

        foreach ($participant->comments as $comment) {
            $this->objectManager->forceDelete($comment);
        }

        if ($participant->user) {
            $this->objectManager->forceDelete($participant->user);
        }

        $this->objectManager->save($participant);
    }
}
