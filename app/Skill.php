<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // List all skills from the database keyed by id
    public static function formArray()  // TODO better method name
    {
        return Skill::all()->pluck('tag', 'id');
    }

    // A skill belongs to many members
    public function members()
    {
        return $this->belongsToMany('App\Member')->withTimestamps();
    }
}
