<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // A review has many members
    public function members()
    {
        return $this->belongsToMany(Member::class)
            ->withPivot('stof')
            ->withPivot('aandacht')
            ->withPivot('mening')
            ->withPivot('tevreden')
            ->withPivot('bericht');
    }

    // A review belongs to one event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Kampkeuze should be an array but is stored as a string
    public function getKampkeuzeAttribute($value)
    {
        return explode(', ', $value);
    }
}
