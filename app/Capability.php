<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
    public static function findByName($n)
    {
        return Capability::where("name", "=", $n)->get();
    }

    public static function findByNames($n)
    {
        return Capability::whereIn("name", $n)->get();
    }

    public $timestamps = false;
    protected $guarded = ['id'];

    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_capability');
    }
}
