<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

	use Authenticatable, CanResetPassword, Notifiable ;

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
		return $this->belongsToMany('App\Role', 'user_role');
	}

	public function hasRole($tag)
	{
		return $this->roles()->with("tag", "=", $tag)->count() > 0;
	}

	public function capabilities()
	{
		return Capability::whereHas("roles", function ($q) {
			$q->whereIn(
				"role_id",
				Auth::user()->roles()->lists('id')
			);
		});
	}

	public function isMember()
	{
		return $this->profile_type === "App\Member";
	}

	public function isParticipant()
	{
		return $this->profile_type === "App\Participant";
	}
}
