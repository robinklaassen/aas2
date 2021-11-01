<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // A location has many events
    public function events()
    {
        return $this->hasMany('App\Models\Event');
    }

    public function comments()
    {
        return $this->morphMany('App\Models\Comment', 'entity');
    }
}
