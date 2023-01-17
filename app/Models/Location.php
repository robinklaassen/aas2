<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\LocationUpdated;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

/**
 * @property Point $geolocatie
 */
class Location extends Model
{
    use HasSpatial;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'geolocatie' => Point::class,
    ];

    protected $dispatchesEvents = [
        'updated' => LocationUpdated::class,
    ];

    public function getVolledigAdresAttribute(): string
    {
        return $this->adres . ', ' . $this->postcode . ' ' . $this->plaats;
    }

    // A location has many events
    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'entity');
    }
}
