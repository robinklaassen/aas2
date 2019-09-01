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
		return $this->profile->volnaam;
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
