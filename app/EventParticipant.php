<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $guarded =  ['created_at', 'updated_at'];
    public function participant()
    {
        return $this->hasOne("App\Participant");
    }

    public function event()
    {
        return $this->hasOne("App\Event");
    }

    public function package()
    {
        return $this->hasOne("App\EventPackage");
    }
}
