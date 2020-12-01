<?php

declare(strict_types=1);

namespace App\Services\Anonymize;

use App\Participant;
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

    public function __construct(AnonymizeGeneratorInterface $generator)
    {

        $this->generator = $generator;
    }

    /** @return Collection<Participant> */
    public function getParticipantsToAnonymize(DateTimeImmutable $currentTime): Collection
    {
        $inactiveBoundary = (new Carbon($currentTime))->subYears(self::YEAR_NO_CAMP);

        return Participant::query()
            ->leftJoin('event_participant', 'participant.id', '=', 'event_participant.participant_id')
            ->where('event_participant.created_at', '<=', $inactiveBoundary)
            ->orWhere(function ($query) use ($inactiveBoundary) {
                /** @var Builder $query */
                $query->whereIsNull('event_participant.created_at')
                    ->where('participant.created_at', '<=', $inactiveBoundary);
            })
            ->select('participant.*')
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
        $participant->opmerking = "Geänonimiseerd";

        $participant->comments()->forceDelete();
        $participant->delete();
    }
}
