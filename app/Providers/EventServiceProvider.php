<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\FinishedEvent;
use App\Events\LocationUpdated;
use App\Events\MemberUpdated;
use App\Listeners\AddMemberActionForFinishedEvent;
use App\Listeners\QueueLocationGeolocation;
use App\Listeners\QueueMemberGeolocation;
use App\Listeners\SetLastLoginDate;
use App\Services\ActionGenerator\EventSingleActionApplicator;
use App\Services\ActionGenerator\EventStraightFlushActionApplicator;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Foundation\Application;
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
        LocationUpdated::class => [
            QueueLocationGeolocation::class,
        ],
        FinishedEvent::class => [
            AddMemberActionForFinishedEvent::class,
        ],
    ];

    public function register()
    {
        parent::register();

        $this->app->tag(
            [EventSingleActionApplicator::class, EventStraightFlushActionApplicator::class],
            'eventaction.applicator'
        );
        $this->app->bind(AddMemberActionForFinishedEvent::class, function (Application $app) {
            return new AddMemberActionForFinishedEvent($app->tagged('eventaction.applicator'));
        });
    }
}
