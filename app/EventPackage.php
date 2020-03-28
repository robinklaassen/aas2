<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventPackage extends Model
{
    const TYPE_DESCRIPTIONS = [
        "online-tutoring"   => "Online bijles",
        "other"             => "Anders"
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
