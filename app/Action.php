<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model {

	protected $guarded = ['id', 'created_at', 'updated_at'];
	
	// Carbon dates
	protected $dates = ['date'];
	
	// An action belongs to one member
	
	public function member()
	{
		return $this->belongsTo('App\Member');
	}
	
}
