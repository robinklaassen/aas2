<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\MemberUpdated;
use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

/**
 * @property Point $geolocatie
 */
class Member extends Model
{
    use FormAccessible;

    use HasSpatial;

    // Number of points needed for every level in the points system
    public const RANK_POINTS = [0, 3, 10, 20, 35, 50, 70, 100];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'geboortedatum' => 'datetime',
        'geolocatie' => Point::class,
    ];

    protected $dispatchesEvents = [
        'saved' => MemberUpdated::class,
    ];

    // Full name
    public function getVolnaamAttribute()
    {
        return str_replace('  ', ' ', $this->voornaam . ' ' . $this->tussenvoegsel . ' ' . $this->achternaam);
    }

    // Full address as single string
    public function getVolledigAdresAttribute()
    {
        return $this->adres . ', ' . $this->postcode . ' ' . $this->plaats;
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
        return $this->belongsToMany(Event::class)->withTimestamps()->withPivot('wissel')->withPivot('wissel_datum_start')->withPivot('wissel_datum_eind');
    }

    // A member belongs to many courses
    public function courses()
    {
        return $this->belongsToMany(Course::class)->withPivot('klas');
    }

    // A member belongs to many skills
    public function skills()
    {
        return $this->belongsToMany(Skill::class)->withTimestamps();
    }

    // A member can have one user account
    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }

    // A Member can have comments
    public function comments()
    {
        return $this->morphMany(Comment::class, 'entity');
    }

    // A member has many actions
    public function actions()
    {
        return $this->hasMany(Action::class);
    }

    // A member has many declarations
    public function declarations()
    {
        return $this->hasMany(Declaration::class);
    }

    // A member belongs to many reviews
    public function reviews()
    {
        return $this->belongsToMany(Review::class)
            ->withPivot('stof')
            ->withPivot('aandacht')
            ->withPivot('mening')
            ->withPivot('tevreden')
            ->withPivot('bericht');
    }

    // Custom getter for amount of points for this member
    public function getPointsAttribute(): int
    {
        return (int) $this->actions()->sum('points');
    }

    // Custom getter for current rank (level in the points system) of this member
    public function getRankAttribute(): int
    {
        $points = $this->points;

        foreach ($this::RANK_POINTS as $level => $number) {
            if ($points >= $number) {
                $rank = $level;
            }
        }

        return $rank;
    }

    public function getIsMaxedRankAttribute(): bool
    {
        return $this->rank === array_key_last($this::RANK_POINTS);
    }

    public function getPointsToNextRankAttribute(): int
    {
        if ($this->is_maxed_rank) {
            return 0;
        }

        $points_next_rank = $this::RANK_POINTS[$this->rank + 1];
        return $points_next_rank - $this->points;
    }

    public function getPercentageToNextRankAttribute(): int
    {
        if ($this->is_maxed_rank) {
            return 100;
        }

        $current = $this->points;
        $start = $this::RANK_POINTS[$this->rank];
        $end = $this::RANK_POINTS[$this->rank + 1];

        return (int) round(($current - $start) / ($end - $start) * 100);
    }

    public function getMostRecentActionAttribute()
    {
        $lastAction = $this->actions()
            ->orderBy('date', 'desc')
            ->first();

        return $lastAction ?: Action::empty();
    }

    // Get list of unique other members that this person has been on camp with
    public function getFellowsAttribute()
    {
        $events = $this->events()
            ->where('type', 'kamp')
            ->where('datum_eind', '<', date('Y-m-d'))
            ->get();

        $fellow_ids = [];
        foreach ($events as $event) {
            $fellow_ids = array_merge($fellow_ids, $event->members()->pluck('id')->toArray());
        }
        $fellow_ids = array_unique($fellow_ids);

        $key = array_search($this->id, $fellow_ids, true);
        if ($key !== false) {
            unset($fellow_ids[$key]);
        }
        $fellows = self::whereIn('id', $fellow_ids)->orderBy('voornaam')->get();
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
            'email' => $this->email_anderwijs,
            'name' => $this->volnaam,
        ];
    }

    public function isUser(User $user)
    {
        return $this->user !== null && $this->user->id === $user->id;
    }

    public function formSkillsAttribute($value)
    {
        return $this->skills()->pluck('id');
    }

    public function getNextFutureCamp(): ?Event
    {
        return $this->events()
            ->where('type', 'kamp')
            ->where('datum_start', '>', Carbon::now())
            ->orderBy('datum_start')
            ->first();
    }

    public function scopeOnEvent($query, Event $event)
    {
        return $query->whereHas('events', function ($q) use ($event) {
            $q->where('id', $event->id);
        });
    }

    public function scopeBirthday($query)
    {
        return $query
            ->whereMonth('geboortedatum', Carbon::now()->month)
            ->whereDay('geboortedatum', Carbon::now()->day);
    }
}
