<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\EventSaved;
use App\Events\FinishedEvent;
use App\Exceptions\MethodNotAllowedException;
use App\Pivots\EventParticipant;
use App\ValueObjects\Pricing\Discount;
use Carbon\Carbon;
use Collective\Html\Eloquent\FormAccessible;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use FormAccessible;

    public const TYPE_DESCRIPTIONS = [
        'kamp' => 'Kamp',
        'training' => 'Training',
        'overig' => 'Overig',
        'online' => 'Online',
    ];

    // Descriptions of the camp types (used in reviews)
    public const CAMP_DESCRIPTIONS = [
        'herfst' => 'Herfstkamp (weekend in de herfstvakantie)',
        'winter' => 'Winterkamp (halve week voor Kerst of na Oud en Nieuw)',
        'voorjaar' => 'Voorjaarskamp (weekend in de voorjaarsvakantie)',
        'paas' => 'Paaskamp (lang weekend met Pasen)',
        'mei' => 'Meikamp (week in de meivakantie, vlak voor de eindexamens)',
        'hemelvaart' => 'Hemelvaartskamp (lang weekend met Hemelvaart, vlak voor de laatste toetsweek van niet-eindexamenklassen)',
        'zomer' => 'Zomerkamp (week in de zomervakantie)',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'datum_voordag' => 'datetime',
        'datum_start' => 'datetime',
        'datum_eind' => 'datetime',
        'vroegboek_korting_datum_eind' => 'datetime',
    ];

    protected $dispatchesEvents = [
        'saved' => EventSaved::class,
    ];

    // A camp belongs to many members
    public function members()
    {
        return $this->belongsToMany(Member::class)->withTimestamps()
            ->withPivot('wissel')
            ->withPivot('wissel_datum_start')
            ->withPivot('wissel_datum_eind');
    }

    // A camp belongs to many participants
    public function participants()
    {
        return $this->belongsToMany(Participant::class)
            ->using(EventParticipant::class)
            ->withTimestamps()
            ->withPivot(['package_id', 'geplaatst', 'datum_betaling']);
    }

    // A camp belongs to one location
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    // A camp has many reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'entity');
    }

    // Get average rating for this camp
    public function getAverageRatingAttribute()
    {
        if (! $this->has('reviews')) {
            return null;
        }

        return round($this->reviews()->pluck('cijfer')->avg(), 1);
    }

    public function hasUser(User $user)
    {
        return $user->profile->events->contains($this);
    }

    public function getFullTitleAttribute()
    {
        return $this->naam . ' ' . substr((string) $this->datum_start, 0, 4) .
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

    public function formVroegboekKortingDatumEindAttribute(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        return Carbon::parse($value)->format('Y-m-d');
    }

    // The association year ('verenigingsjaar') starts at September 1st. This returns a string e.g. '14-15'
    public function getVerenigingsjaarAttribute(): string
    {
        $startYear = $this->datum_start->subMonths(9)->year;
        $endYear = $startYear + 1;
        return substr((string) $startYear, -2) . '-' . substr((string) $endYear, -2);
    }

    public function getStreeftalDeelnemersAttribute(): int
    {
        return ($this->streeftal - 1) * 3;
    }

    public function getHasEarlybirdDiscountAttribute(): bool
    {
        return $this->vroegboek_korting_percentage !== null
            && $this->vroegboek_korting_datum_eind !== null
            && Carbon::now()->lessThan($this->vroegboek_korting_datum_eind);
    }

    public function getEarlybirdDiscountAttribute(): Discount
    {
        if (! $this->hasEarlybirdDiscount) {
            return Discount::none();
        }

        return Discount::fromPercentage($this->vroegboek_korting_percentage);
    }

    /**
     * Event is publicly visible
     */
    public function scopePublic($query)
    {
        return $query->where('openbaar', 1);
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

    public function scopeEnded($query)
    {
        return $query->where('datum_eind', '<', Carbon::now());
    }

    public function scopeNotCancelled($query)
    {
        return $query->where('cancelled_at', null);
    }

    public function finalize(): void
    {
        if ($this->finalized_at !== null) {
            return;
        }

        FinishedEvent::dispatch($this);
        $this->finalized_at = Carbon::now();
        $this->save();
    }

    public function getFulltimeMembersWhereVOG(bool $vog): EloquentCollection
    {
        return $this->members()->wherePivot('wissel', 0)->where('vog', $vog)->get();
    }

    public function getParticipantsWherePaid(bool $paid): EloquentCollection
    {
        $operator = $paid ? '>' : '=';
        return $this->participants()->wherePivot('datum_betaling', $operator, '0000-00-00')->get();
    }

    public function training(): ?self
    {
        if ($this->type !== 'kamp') {
            throw new MethodNotAllowedException('Cannot call training() on event not of type `kamp`.');
        }

        return self::where('type', 'training')->where('code', sprintf('T%s', $this->code))->first();
    }

    public function camps(): EloquentCollection
    {
        if ($this->type !== 'training') {
            throw new MethodNotAllowedException('Cannot call camps() on event not of type `training`.');
        }

        return self::where('type', 'kamp')->where('code', 'like', substr($this->code, 1) . '%')->get();
    }
}
