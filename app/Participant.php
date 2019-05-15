<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model {

	protected $guarded = ['id', 'created_at', 'updated_at'];

	// Carbon dates
	protected $dates = ['geboortedatum', 'inkomensverklaring'];

	// Full name
	public function getVolnaamAttribute() {
		return str_replace('  ', ' ', $this->voornaam . ' ' . $this->tussenvoegsel . ' ' . $this->achternaam);
	}

	// Postcode mutator
	public function setPostcodeAttribute($value)
	{
		$value = strtoupper($value);
		if (preg_match('/\d{4}[A-Z]{2}/', $value))
		{
			$this->attributes['postcode'] = substr($value,0,4) . ' ' . substr($value,4,2);
		}
		else
		{
			$this->attributes['postcode'] = $value;
		}
	}

	// A participant belongs to many events
	public function events()
	{
		return $this->belongsToMany('App\Event')->withTimestamps()->withPivot('datum_betaling')->withPivot('geplaatst');
	}

	// A participant can have one user account
	public function user()
	{
		return $this->morphOne('App\User', 'profile');
	}
}
