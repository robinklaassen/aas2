<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    public static function findByName($n)
    {
        return self::where('name', '=', $n)->get();
    }

    public static function findByNames($n)
    {
        return self::whereIn('name', $n)->get();
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_capability');
    }
}
