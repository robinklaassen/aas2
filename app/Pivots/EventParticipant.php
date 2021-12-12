<?php

declare(strict_types=1);

namespace App\Pivots;

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
}
