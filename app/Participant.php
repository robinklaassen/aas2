<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{

	const INCOME_DESCRIPTION_TABLE = [
		0 => 'Meer dan € 3400 (geen korting)',
		1 => 'Tussen € 2200 en € 3400 (korting: 15%)',
		2 => 'Tussen € 1300 en € 2200 (korting: 30%)',
		3 => 'Minder dan € 1300 (korting: 50%)'
	];
	const INCOME_DISCOUNT_TABLE = [
		0 => 1.0,
		1 => 0.85,
		2 => 0.7,
		3 => 0.5
	];

	protected $guarded = ['id', 'created_at', 'updated_at'];

	// Carbon dates
	protected $dates = ['geboortedatum', 'inkomensverklaring'];

	// Full name
	public function getVolnaamAttribute()
	{
		return str_replace('  ', ' ', $this->voornaam . ' ' . $this->tussenvoegsel . ' ' . $this->achternaam);
	}

	// Postcode mutator
	public function setPostcodeAttribute($value)
	{
		$value = strtoupper($value);
		if (preg_match('/\d{4}[A-Z]{2}/', $value)) {
			$this->attributes['postcode'] = substr($value, 0, 4) . ' ' . substr($value, 4, 2);
		} else {
			$this->attributes['postcode'] = $value;
		}
	}

	public function getParentEmail()
	{
		return [
			"email" => $this->email_ouder,
			"name"  => $this->parentName,
		];
	}

	public function getParentNameAttribute()
	{
		return 'dhr./mw. ' . $this->tussenvoegsel . ' ' . $this->achternaam;
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

	public function comments()
	{
		return $this->morphMany('App\Comment', 'entity');
	}

	public function getIncomeDescriptionAttribute()
	{
		return $this::INCOME_DESCRIPTION_TABLE[$this->inkomen];
	}

	public function getIncomeBasedDiscountAttribute(): float
	{
		return $this::INCOME_DISCOUNT_TABLE[$this->inkomen];
	}

	public function isUser(User $user)
	{
		return $this->user->id === $user->id;
	}
}
