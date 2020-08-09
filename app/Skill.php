<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // A skill belongs to many members
    public function members()
    {
        return $this->belongsToMany('App\Member')->withTimestamps();
    }
}
