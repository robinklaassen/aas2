<?php

declare(strict_types=1);

namespace App\Helpers\Payment;

use App\Models\Event;
use App\Models\EventPackage;
use App\Models\Participant;

class EventPayment implements PaymentInterface
{
    private $event;

    private $package;

    private $participant;

    private $existing;

    public static function calculate_price(?int $fullprice, float $discount = 1.0)
    {
        return round(($discount * $fullprice) / 5) * 5;
    }

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

    public function existing(bool $existing = true)
    {
        $this->existing = $existing;
        return $this;
    }

    public function getPackagePrice(): int
    {
        return $this->package === null ? 0 : $this->package->price;
    }

    public function getTotalAmount(): float
    {
        return self::calculate_price($this->event->prijs + $this->getPackagePrice(), $this->participant->incomeBasedDiscount);
    }

    public function getDescription(): string
    {
        if ($this->package !== null) {
            return $this->event->code . ' - ' . $this->package->code . ' - ' . $this->participant->volnaam;
        }
        return $this->event->code . ' - ' . $this->participant->volnaam;
    }

    public function getCurrency(): string
    {
        return 'EUR';
    }

    public function getRedirectUrl(): string
    {
        return url("iDeal-response/{$this->participant->id}/{$this->event->id}");
    }

    public function getMetadata()
    {
        return [
            'participant_id' => $this->participant->id,
            'camp_id' => $this->event->id,
            'type' => $this->existing ? 'existing' : 'new',
        ];
    }
}
