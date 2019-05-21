<?php
namespace App\Helpers\Payment;

use App\Event;
use App\Participant;

class EventPayment implements PaymentInterface
{
    private $event;
    private $participant;
    private $existing;

    public function event(Event $event)
    {
        $this->event = $event;
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

    public function getTotalAmount(): float
    {
        return round(($this->participant->incomeBasedDiscount() * $this->event->prijs) / 5) * 5;
    }

    public function getDescription(): string
    {
        return $this->event->code . " - " . str_replace("  ", " ", $this->participant->voornaam . " " . $this->participant->tussenvoegsel . " " . $this->participant->achternaam);
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
