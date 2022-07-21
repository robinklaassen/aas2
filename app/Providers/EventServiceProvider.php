<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\EventSaved;
use App\Events\FinishedEvent;
use App\Events\LocationUpdated;
use App\Events\MemberUpdated;
use App\Listeners\AddMemberActionForFinishedEvent;
use App\Listeners\QueueLocationGeolocation;
use App\Listeners\QueueMemberGeolocation;
use App\Listeners\QueueUpdateWebsite;
use App\Listeners\SetLastLoginDate;
use App\Services\ActionGenerator\EventSingleActionApplicator;
use App\Services\ActionGenerator\EventStraightFlushActionApplicator;
use App\Services\WebsiteUpdater\WebsiteUpdater;
use App\Services\WebsiteUpdater\WebsiteUpdaterThroughGithubActions;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
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
        EventSaved::class => [
            QueueUpdateWebsite::class,
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

        $this->app->bind(WebsiteUpdater::class, function (Application $app) {
            return new WebsiteUpdaterThroughGithubActions(
                new HttpClient(),
                config('website.github.repository'),
                config('website.github.token'),
            );
        });
    }
}
