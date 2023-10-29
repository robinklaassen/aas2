<?php

declare(strict_types=1);

namespace App\Models;

use App\Pivots\EventParticipant;
use App\ValueObjects\Pricing\Discount;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    public const INCOME_DESCRIPTION_TABLE = [
        0 => 'Meer dan € 3400 (geen korting)',
        1 => 'Tussen € 2400 en € 3400 (korting: 20%)',
        2 => 'Tussen € 1600 en € 2400 (korting: 40%)',
        3 => 'Minder dan € 1600 (korting: 100%)',
    ];

    public const INCOME_DISCOUNT_TABLE = [
        0 => 0,
        1 => 20,
        2 => 40,
        3 => 100,
    ];

    public const INFORMATION_CHANNEL_DESCRIPTION_TABLE = [
        'postal-and-email' => 'Post en e-mail',
        'only-email' => 'Alleen e-mail',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'geboortedatum' => 'datetime',
        'inkomensverklaring' => 'datetime',
    ];

    // Full name
    public function getVolnaamAttribute()
    {
        return str_replace('  ', ' ', $this->voornaam . ' ' . $this->tussenvoegsel . ' ' . $this->achternaam);
    }

    // Full level (e.g. '4 HAVO')
    public function getVolNiveauAttribute()
    {
        return $this->klas . ' ' . $this->niveau;
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
            'email' => $this->email_ouder,
            'name' => $this->parentName,
        ];
    }

    public function getParentNameAttribute()
    {
        return 'dhr./mw. ' . $this->tussenvoegsel . ' ' . $this->achternaam;
    }

    // A participant belongs to many events
    public function events()
    {
        return $this->belongsToMany(Event::class)
            ->using(EventParticipant::class)
            ->withTimestamps()
            ->withPivot(['package_id', 'geplaatst', 'datum_betaling']);
    }

    // A participant can have one user account
    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'entity');
    }

    public function getIncomeDescriptionAttribute()
    {
        return $this::INCOME_DESCRIPTION_TABLE[$this->inkomen];
    }

    public function getIncomeBasedDiscountAttribute(): Discount
    {
        return Discount::fromPercentage($this::INCOME_DISCOUNT_TABLE[$this->inkomen]);
    }

    public function getInformationChannelDescriptionAttribute()
    {
        return $this::INFORMATION_CHANNEL_DESCRIPTION_TABLE[$this->information_channel];
    }

    public function isUser(User $user)
    {
        return $this->user && $this->user->id === $user->id;
    }

    public function getLastPlacedCampAttribute(): ?Event
    {
        return $this->events()
            ->wherePivot('geplaatst', '=', true)
            ->whereIn('type', ['kamp', 'online'])
            ->latest('datum_start')
            ->first();
    }

    public function scopeNonAnonymized(Builder $query)
    {
        return $query->whereNull('anonymized_at');
    }
}
