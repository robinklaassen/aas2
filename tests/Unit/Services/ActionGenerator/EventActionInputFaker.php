<?php

declare(strict_types=1);

namespace Tests\Unit\Services\ActionGenerator;

use App\Models\Action;
use App\Models\Event;
use App\Models\Member;
use App\Services\ActionGenerator\ValueObject\EventActionInput;
use Carbon\Carbon;

final class EventActionInputFaker
{
    private array $codes = [];

    private bool $wissel = false;

    private array $eventData;

    private function __construct()
    {
        $this->eventData = [
            'code' => ':code:',
            'description' => ':description:',
            'datum_start' => Carbon::now(),
            'type' => 'kamp',
        ];
    }

    public static function create(): self
    {
        return new self();
    }

    public function withAction(string $code = ':code:', int $points = 3, string $description = ':description:'): self
    {
        $act = new Action();

        $act->fill([
            'code' => $code,
            'points' => $points,
            'description' => $description,
        ]);

        $this->codes[] = $act;
        return $this;
    }

    public function withWissel(bool $wissel = true): self
    {
        $this->wissel = $wissel;
        return $this;
    }

    public function withEventData(
        string $code = 'K2122',
        string $description = ':description:',
        string $dateStart = '2021-10-11',
        string $dateEnd = '2021-10-11',
        string $type = 'kamp'
    ): self {
        $this->eventData['code'] = $code;
        $this->eventData['naam'] = $description;
        $this->eventData['datum_start'] = Carbon::create($dateStart);
        $this->eventData['datum_eind'] = Carbon::create($dateEnd);
        $this->eventData['type'] = $type;

        return $this;
    }

    public function withEventType(string $type): self
    {
        $this->eventData['type'] = $type;

        return $this;
    }

    public function build(): EventActionInput
    {
        $event = Event::create($this->eventData);
        $member = Member::create();

        $member->actions()->saveMany($this->codes);

        return new EventActionInput($event, $member, $this->wissel);
    }

    public function withCancelledEvent(): self
    {
        $this->eventData['cancelled_at'] = Carbon::now();
        return $this;
    }
}
