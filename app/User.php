<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

	use Authenticatable, CanResetPassword, Notifiable;

	/**
	 * Generates a new new password for users
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
