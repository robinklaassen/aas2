<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventPackage extends Model
{
    public const TYPE_DESCRIPTIONS = [
        'online-tutoring' => 'Online bijles',
        'other' => 'Anders',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function participants()
    {
        return $this->belongsToMany('App\Models\Participant')->using('App\Pivots\EventParticipant');
    }

    public function events()
    {
        return $this->belongsToMany('App\Models\Event')->using('App\Pivots\EventParticipant');
    }
}
