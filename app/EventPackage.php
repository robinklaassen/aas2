<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventPackage extends Model
{
    const TYPE_DESCRIPTIONS = [
        "online-tutoring"   => "Online bijles",
        "other"             => "Anders",
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function participants()
    {
        return $this->belongsToMany('App\Participant')->using('App\Pivots\EventParticipant');
    }

    public function events()
    {
        return $this->belongsToMany('App\Event')->using('App\Pivots\EventParticipant');
    }
}
