<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventParticipant extends Pivot
{
    protected $guarded = ['created_at', 'updated_at'];
    public function participant()
    {
        return $this->belongsTo("App\Participant");
    }

    public function event()
    {
        return $this->belongsTo("App\Event");
    }

    public function package()
    {
        return $this->belongsTo("App\EventPackage");
    }
}
