<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = ['role_id'];

	public function users() {
		return $this->belongsToMany('App\User')->withTimestamps()->using("App\UserRole");
	}    
}
