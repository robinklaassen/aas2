<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, Notifiable, Authorizable;

    /**
     * Generates a new password for users
     * Excludes lookalike characters: O 0 o, i I L l, V v W w, s S 5
     */
    public static function generatePassword(): string
    {
        $chars = "abcdefghjklmnpqrtuxyzABCDEFGHJKMNPQRTUXYZ2346789";
        $password = substr(str_shuffle($chars), 0, 10);

        return $password;
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    // Add last login to Carbon dates
    protected $dates = ['last_login'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'password', 'is_admin'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    # Polymorphic relation to either member or participant profile
    public function profile()
    {
        return $this->morphTo();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function hasRole($tag)
    {
        return $this->roles()->where("tag", "=", $tag)->count() > 0;
    }

    public function public_roles()
    {
        return $this->roles()->whereNotIn("tag", Role::HIDDEN_ROLES);
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn("tag", $roles)->count() > 0;
    }

    public function capabilities()
    {
        $roles = $this->roles()->pluck('id');

        return Capability::whereHas("roles", function ($q) use ($roles) {
            $q->whereIn(
                "id",
                $roles
            );
        })->get();
    }

    public function hasCapability($name)
    {
        return $this->capabilities()->pluck("name")->contains($name);
    }

    public function isMember()
    {
        return $this->profile_type === Member::class;
    }

    public function isParticipant()
    {
        return $this->profile_type === Participant::class;
    }

    public function getVolnaamAttribute()
    {
        if ($this->id != 0) {
            return $this->profile->volnaam;
        } else {
            return "-system-";
        }
    }

    public function getPrivacyAcceptedAttribute()
    {
        return $this->privacy !== null;
    }

    public function setPrivacyAcceptedAttribute(bool $v)
    {
        $this->attributes['privacy'] = $v ? Carbon::now() : null;
    }
}
