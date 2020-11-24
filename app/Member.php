<?php

namespace App;

use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Member extends Model
{

	use FormAccessible;

	protected $guarded = ['id', 'created_at', 'updated_at'];

	protected $dates = ['created_at', 'updated_at', 'geboortedatum'];


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

	// A member belongs to many events
	public function events()
	{
		return $this->belongsToMany('App\Event')->withTimestamps()->withPivot('wissel')->withPivot('wissel_datum_start')->withPivot('wissel_datum_eind');
	}

	// A member belongs to many courses
	public function courses()
	{
		return $this->belongsToMany('App\Course')->withPivot('klas');
	}

	// A member belongs to many skills
	public function skills()
	{
		return $this->belongsToMany('App\Skill')->withTimestamps();
	}

	// A member can have one user account
	public function user()
	{
		return $this->morphOne('App\User', 'profile');
	}

	// A Member can have comments
	public function comments()
	{
		return $this->morphMany('App\Comment', 'entity');
	}

	// A member has many actions
	public function actions()
	{
		return $this->hasMany('App\Action');
	}

	// A member has many declarations
	public function declarations()
	{
		return $this->hasMany('App\Declaration');
	}

	// A member belongs to many reviews
	public function reviews()
	{
		return $this->belongsToMany('App\Review')
			->withPivot('stof')
			->withPivot('aandacht')
			->withPivot('mening')
			->withPivot('tevreden')
			->withPivot('bericht');
	}

	// Custom getter for the 'straight flush' - a member having done 5 or more unique types of camp (gets 3 bonus points)
	public function getHasstraightflushAttribute()
	{
		$startDate = '2014-09-01';
		$endDate = date('Y-m-d');

		$camps = $this->events()
					->notCancelled()
					->where('type', 'kamp')
					->where('datum_start', '>', $startDate)
					->where('datum_eind', '<', $endDate)
					->where('wissel', 0)
					->get();

		$list = [];
		foreach ($camps as $camp) {
			$list[] = substr($camp->code, 0, 1);
		}

		$ulist = array_unique($list);

		// Filter K and N to 1
		if (in_array('K', $ulist) && in_array('N', $ulist)) {
			$ulist = array_diff($ulist, ['K']);
		}

		$result = (count($ulist) >= 5) ? true : false;

		return $result;
	}

	// Custom getter for amount of points for this member
	public function getPointsAttribute()
	{
		$startDate = '2014-09-01';
		$endDate = date('Y-m-d');

		$base_query = $this->events()
			->notCancelled()
			->where('datum_start', '>', $startDate)
			->where('datum_eind', '<', $endDate);

		$camps_full = (clone $base_query)->where('type', 'kamp')->where('wissel', 0)->count();
		$camps_partial = (clone $base_query)->where('type', 'kamp')->where('wissel', 1)->count();
		$trainings = (clone $base_query)->where('type', 'training')->count();

		$other = $this->actions()->where('date', '<=', $endDate)->sum('points');

		$points = $camps_full * 3 + $camps_partial * 1 + $trainings * 2 + $other;

		if ($this->hasstraightflush) {
			$points += 3;
		}

		return $points;
	}

	// Custom getter for current rank (level in the points system) of this member
	public function getRankAttribute()
	{
		$points = $this->points;

		$ranks = [0, 3, 10, 20, 35, 50, 70, 100]; // this array is also in the view composer for member.show
		foreach ($ranks as $level => $number) {
			if ($points >= $number) $rank = $level;
		}

		return $rank;
	}

	// Custom getter for a list of all actions and their points
	public function getListofactionsAttribute()
	{
		$startDate = '2014-09-01';
		$endDate = date('Y-m-d');
		$data = [];

		// First the event data
		$events = $this->events()
					->notCancelled()
					->whereIn('type', ['kamp', 'training'])
					->where('datum_start', '>', $startDate)
					->where('datum_eind', '<', $endDate)
					->orderBy('datum_start', 'asc')
					->get();

		foreach ($events as $event) {

			$name = $event->naam . ' ' . $event->datum_start->format('Y');
			if ($event->pivot->wissel) {
				$name .= ' (w)';
			}

			if ($event->type == 'training') {
				$points = 2;
			} elseif ($event->pivot->wissel) {
				$points = 1;
			} else {
				$points = 3;
			}

			$data[] = [
				'date' => $event->datum_start,
				'name' => $name,
				'points' => $points
			];
		}

		// Then the action data
		$actions = $this->actions()->where('date', '<=', $endDate)->orderBy('date', 'asc')->get();

		foreach ($actions as $action) {
			$data[] = [
				'date' => $action->date,
				'name' => $action->description,
				'points' => $action->points
			];
		}

		// Then sort and return
		$data = Arr::sort($data, function ($item) {
			return $item['date'];
		});

		if ($this->hasstraightflush) {
			$data[] = [
				'date' => Carbon::now(),
				'name' => 'Straat',
				'points' => 3
			];
		}

		return $data;
	}

	// Custom getter for most recent action
	public function getMostrecentactionAttribute()
	{
		$startDate = '2014-09-01';
		$endDate = date('Y-m-d');

		$lastEvent = $this->events()
						->notCancelled()
						->whereIn('type', ['kamp', 'training'])
						->where('datum_start', '>', $startDate)
						->where('datum_eind', '<', $endDate)
						->orderBy('datum_start', 'desc')
						->first();

		$lastAction = $this->actions()->where('date', '<', $endDate)->orderBy('date', 'desc')->first();

		$e = $lastEvent !== null;
		$a = $lastAction !== null;

		if ($e && $a) {
			$result = ($lastEvent->datum_eind > $lastAction->date) ? 'e' : 'a';
		} elseif ($e) {
			$result = 'e';
		} elseif ($a) {
			$result = 'a';
		} else {
			$result = null;
		}

		switch ($result) {
			case 'e':
				if ($lastEvent->type == 'training') {
					$points = 2;
				} elseif ($lastEvent->pivot->wissel == 0) {
					$points = 3;
				} else {
					$points = 1;
				}

				$data = [
					'name' => $lastEvent->naam . ' ' . $lastEvent->datum_eind->format('Y'),
					'points' => $points
				];
				break;
			case 'a':
				$data = [
					'name' => $lastAction->description,
					'points' => $lastAction->points
				];
				break;
			default:
				$data = [
					'name' => '-',
					'points' => 0
				];
		}

		return $data;
	}

	// Get list of unique other members that this person has been on camp withPivot
	public function getFellowsAttribute()
	{
		$events = $this->events()->where('type', 'kamp')->where('datum_eind', '<', date('Y-m-d'))->get();
		$fellow_ids = [];
		foreach ($events as $event) {
			$fellow_ids = array_merge($fellow_ids, $event->members()->pluck('id')->toArray());
		}
		$fellow_ids = array_unique($fellow_ids);
		if (($key = array_search($this->id, $fellow_ids)) !== false) {
			unset($fellow_ids[$key]);
		}
		$fellows = \App\Member::whereIn('id', $fellow_ids)->orderBy('voornaam')->get();
		return $fellows;
	}

	public function hasRole($title)
	{
		$user = $this->user()->first();
		return $user ? $user->hasRole($title) : false;
	}

	public function getAnderwijsEmail()
	{
		return [
			"email" => $this->email_anderwijs,
			"name" => $this->volnaam
		];
	}

	public function isUser(User $user)
	{
		return $this->user->id === $user->id;
	}

	public function formSkillsAttribute($value)
	{
		return $this->skills()->pluck('id');
	}
}
