<?php

namespace App\Providers;

use App\Listeners\SetLastLoginDate;
use App\Listeners\UpdateMemberGeolocation;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\MemberUpdated;

class EventServiceProvider extends ServiceProvider
{

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		Login::class => [
			SetLastLoginDate::class,
		],
		MemberUpdated::class => [
			UpdateMemberGeolocation::class
		]
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot()
	{
		parent::boot();

		//
	}
}
