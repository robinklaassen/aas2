<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {

	protected $guarded = ['id', 'created_at', 'updated_at'];
	
	// A course belongs to many members
	public function members()
	{
		return $this->belongsToMany('App\Member')->withPivot('klas');
	}

}
