<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model {

	protected $guarded = ['id', 'created_at', 'updated_at'];
	
	// Carbon dates
	public function getDates()
	{
		return array('created_at', 'updated_at', 'datum_voordag', 'datum_start', 'datum_eind');
	}
	
	// A camp belongs to many members
	public function members()
	{
		return $this->belongsToMany('App\Member')->withTimestamps()->withPivot('wissel')->withPivot('wissel_datum_start')->withPivot('wissel_datum_eind');
	}
	
	// A camp belongs to many participants
	public function participants()
	{
		return $this->belongsToMany('App\Participant')->withTimestamps()->withPivot('datum_betaling')->withPivot('geplaatst');
	}
	
	// A camp belongs to one location
	public function location()
	{
		return $this->belongsTo('App\Location');
	}
	
	// A camp has many reviews
	public function reviews()
	{
		return $this->hasMany('App\Review');
	}
	
	// Get average rating for this camp
	public function getAverageRatingAttribute()
	{
		if (!$this->has("reviews"))
			return null;
		
		return round($this->reviews()->pluck("cijfer")->avg(), 1);
	}
	
}
