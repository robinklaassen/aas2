<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\MemberUpdated;
use App\Listeners\QueueMemberGeolocation;
use App\Listeners\SetLastLoginDate;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
            QueueMemberGeolocation::class,
        ],
    ];

    /**
     * Register any other events for your application.
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
