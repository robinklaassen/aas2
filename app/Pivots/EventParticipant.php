<?php

declare(strict_types=1);

namespace App\Pivots;

use App\Helpers\Payment\EventPayment;
use App\Models\Event;
use App\Models\EventPackage;
use App\Models\Participant;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EventParticipant extends Pivot
{
    protected $guarded = ['created_at', 'updated_at'];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function package()
    {
        return $this->belongsTo(EventPackage::class);
    }

    public function isPaid(): bool
    {
        return $this->datum_betaling !== '0000-00-00';
    }

    public function getPayment(bool $existing = true): EventPayment
    {
        return (new EventPayment())
            ->event($this->event)
            ->participant($this->participant)
            ->package($this->package)
            ->existing($existing);
    }
}
