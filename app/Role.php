<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	public $timestamps = false;
	protected $guarded = ['id'];

	public function users() {
		return $this->belongsToMany('App\User', 'user_role');
	}

	public function capabilities() {
		return $this->belongsToMany('App\Capability', 'role_capability');
	}
}
