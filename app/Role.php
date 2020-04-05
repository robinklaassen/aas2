<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

	// Roles which are hidden in the index view for non administrative members
	const HIDDEN_ROLES = [
		// member is implicit; every member has this role
		"member",
		// participant is implicit; every participant has this role
		"participant"
	];

	public $timestamps = false;
	protected $guarded = ['id'];

	public function users()
	{
		return $this->belongsToMany('App\User', 'user_role');
	}

	public function capabilities()
	{
		return $this->belongsToMany('App\Capability', 'role_capability');
	}
}
