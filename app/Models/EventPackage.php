<?php

declare(strict_types=1);

namespace App\Models;

use App\Pivots\EventParticipant;
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
        return $this->belongsToMany(Participant::class)->using(EventParticipant::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class)->using(EventParticipant::class);
    }
}
