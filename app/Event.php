<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{

	const TYPE_DESCRIPTIONS = [
		'kamp' => 'Kamp',
		'training' => 'Training',
		'overig' => 'Overig',
		'online' => 'Online'
	];

	// Descriptions of the camp types (used in reviews)
	const CAMP_DESCRIPTIONS = [
		"herfst" 		=> "Herfstkamp (weekend in de herfstvakantie)",
		"winter" 		=> "Winterkamp (halve week voor Kerst of na Oud en Nieuw)",
		"voorjaar" 		=> "Voorjaarskamp (weekend in de voorjaarsvakantie)",
		"paas" 			=> "Paaskamp (lang weekend met Pasen)",
		"mei" 			=> "Meikamp (week in de meivakantie, vlak voor de eindexamens)",
		"hemelvaart" 	=> "Hemelvaartskamp (lang weekend met Hemelvaart, vlak voor de laatste toetsweek van niet-eindexamenklassen)",
		"zomer" 		=> "Zomerkamp (week in de zomervakantie)"
	];

	protected $guarded = ['id', 'created_at', 'updated_at'];

	// Carbon dates
	protected $dates = ['created_at', 'updated_at', 'datum_voordag', 'datum_start', 'datum_eind'];

	// A camp belongs to many members
	public function members()
	{
		return $this->belongsToMany('App\Member')->withTimestamps()->withPivot('wissel')->withPivot('wissel_datum_start')->withPivot('wissel_datum_eind');
	}

	// A camp belongs to many participants
	public function participants()
	{
		return $this->belongsToMany('App\Participant')
			->using('App\Pivots\EventParticipant')
			->withTimestamps()
			->withPivot(['package_id', 'geplaatst', 'datum_betaling']);
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

	public function comments()
	{
		return $this->morphMany('App\Comment', 'entity');
	}

	// Get average rating for this camp
	public function getAverageRatingAttribute()
	{
		if (!$this->has("reviews"))
			return null;

		return round($this->reviews()->pluck("cijfer")->avg(), 1);
	}

	public function hasUser(User $user)
	{
		return $user->profile->events->contains($this);
	}

	public function getFullTitleAttribute()
	{
		return $this->naam . ' ' . substr($this->datum_start, 0, 4) .
			' te ' . $this->location->plaats . ' (' . $this->datum_start->format('d-m-Y') . ')' . ($this->vol ? ' - VOL' : '');
	}

	public function getCancelledAttribute(): bool
	{
		return $this->cancelled_at !== null;
	}

	public function setCancelledAttribute($value)
	{
		$this->attributes['cancelled_at'] = ($value) ? Carbon::now() : null;
	}

	// The association year ('verenigingsjaar') starts at September 1st. This returns a string e.g. '14-15'
	public function getVerenigingsjaarAttribute(): string
	{
		$startYear = $this->datum_start->subMonths(9)->year;
		$endYear = $startYear + 1;
		return substr($startYear, -2) . '-' . substr($endYear, -2);
	}

	/**
	 * Event is publicly visible
	 */
	public function scopePublic($query)
	{
		return $query->where('openbaar', 1)
            ->whereNull('cancelled_at');
	}

	/**
	 * Events which are open for registrations
	 */
	public function scopeOpen($query)
	{
		// List of future camps and online events to register for
		// Note that online events can be registered for after start (until the end)
		return $query
            ->whereNull('cancelled_at')
            ->where(function ($query) {
                return $query->where(function ($query) {
                    return $query->where('type', '!=', 'online')->where('datum_start', '>', date('Y-m-d'));
                })->orWhere(function ($query) {
                    return $query->where('type', '=', 'online')->where('datum_eind', '>', date('Y-m-d'));
                });
            });
	}

	/**
	 * Events which are visible for participants
	 */
	public function scopeParticipantEvent($query)
	{
		return $query->whereIn('type', ['kamp', 'online']);
	}

	/**
	 * Events which are not ended
	 */
	public function scopeOngoing($query)
	{
		return $query->where('datum_eind', '>', date('Y-m-d'));
	}

	/**
	 * Events which are not cancelled
	 */
	public function scopeNotCancelled($query)
	{
		return $query->where('cancelled_at', null);
	}
}
