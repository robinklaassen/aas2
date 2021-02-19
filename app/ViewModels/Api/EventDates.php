<?php

namespace App\ViewModels\Api;

use App\Event;
use App\ViewModels\ViewModel;
use Illuminate\Support\Carbon;
use \DateTimeImmutable;

class EventDates extends ViewModel
{
    protected array $visible = ['preparation', 'start', 'end'];

    private ?DateTimeImmutable $preparation;
    private DateTimeImmutable $start;
    private DateTimeImmutable $end;

    private function __construct(
        ?DateTimeImmutable $preparation,
        DateTimeImmutable $start,
        DateTimeImmutable $end
    ) {

        $this->preparation = $preparation;
        $this->start = $start;
        $this->end = $end;
    }

    public static function fromEvent(Event $event): self
    {
        $start = Carbon::make($event->datum_start)->setTimeFromTimeString($event->tijd_start);
        $end = Carbon::make($event->datum_eind)->setTimeFromTimeString($event->tijd_eind);
        $preparation = $event->datum_voordag !== null ? $event->datum_voordag->toDateTimeImmutable() : null;

        return new self(
            $preparation,
            $start->toDateTimeImmutable(),
            $end->toDateTimeImmutable()
        );
    }

    public function getPreparation(): ?string
    {
        return $this->preparation !== null ? $this->preparation->format(\DateTimeInterface::ATOM) : null;
    }

    public function getStart(): string
    {
        return $this->start->format(\DateTimeInterface::ATOM);
    }

    public function getEnd(): string
    {
        return $this->end->format(\DateTimeInterface::ATOM);
    }
}
