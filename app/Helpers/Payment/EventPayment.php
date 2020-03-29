<?php

namespace App\Helpers\Payment;

use App\Event;
use App\EventPackage;
use App\Participant;

class EventPayment implements PaymentInterface
{
    private $event;
    private $package;
    private $participant;
    private $existing;

    public function event(Event $event)
    {
        $this->event = $event;
        return $this;
    }

    public function package(?EventPackage $package)
    {
        $this->package = $package;
        return $this;
    }

    public function participant(Participant $part)
    {
        $this->participant = $part;
        return $this;
    }

    public function existing(bool $existing)
    {
        $this->existing = $existing;
        return $this;
    }

    public function getPackagePrice(): int
    {
        return $this->package == null ? 0 : $this->package->price;
    }

    public function getTotalAmount(): float
    {
        return round(($this->participant->incomeBasedDiscount * ($this->event->prijs + $this->getPackagePrice())) / 5) * 5;
    }

    public function getDescription(): string
    {
        if ($this->package !== null) {
            return $this->event->code . " - " . $this->package->code . " - " . $this->participant->volnaam;
        } else {
            return $this->event->code . " - " . $this->participant->volnaam;
        }
    }

    public function getCurrency(): string
    {
        return "EUR";
    }

    public function getKeys(): array
    {
        return [
            $this->participant->id,
            $this->event->id,
        ];
    }

    public function getMetadata()
    {
        return [
            "participant_id" => $this->participant->id,
            "camp_id" => $this->event->id,
            "type" => $this->existing ? "existing" : "new"
        ];
    }
}
