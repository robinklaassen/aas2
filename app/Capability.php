<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Capability extends Model
{
    protected $guarded = ['id'];
    protected $timestamps = false;    

    public function roles() {
        return $this->belongsToMany('App\Role', 'role_capability');
    }
}
