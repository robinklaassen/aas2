<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

	protected $guarded = ['id', 'created_at', 'updated_at'];

	// A location has many events
	public function events()
	{
		return $this->hasMany('App\Event');
	}

	public function comments()
	{
		return $this->morphMany('App\Comment', 'entity');
	}
}
