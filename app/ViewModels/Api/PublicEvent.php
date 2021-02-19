<?php

namespace App\ViewModels\Api;

use App\Event;
use App\ViewModels\ViewModel;

class PublicEvent extends ViewModel
{
    protected array $visible = ['code', 'name', 'description', 'dates', 'location', 'pricing'];

    private Event $event;

    private function __construct(Event $event)
    {
        $this->event = $event;
    }

    public static function fromEvent(Event $event)
    {
        return new self($event);
    }

    public function getCode(): string
    {
        return $this->event->code;
    }

    public function getName(): string
    {
        return $this->event->naam;
    }

    public function getDescription(): ?string
    {
        return $this->event->beschrijving;
    }

    public function getDates(): EventDates
    {
        return EventDates::fromEvent($this->event);
    }

    public function getLocation(): PublicEventLocation
    {
        return PublicEventLocation::fromLocation($this->event->location);
    }

    public function getPricing(): EventPricing
    {
        return EventPricing::fromEvent($this->event);
    }
}
