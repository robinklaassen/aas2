<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\LocationUpdated;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use SpatialTrait;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $spatialFields = ['geolocatie'];

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
