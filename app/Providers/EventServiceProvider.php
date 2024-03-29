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
use App\Listeners\QueueUpdateEmailListSubscriptions;
use App\Listeners\QueueUpdateWebsite;
use App\Listeners\SetLastLoginDate;
use App\Services\ActionGenerator\EventSingleActionApplicator;
use App\Services\ActionGenerator\EventStraightFlushActionApplicator;
use App\Services\DirectAdmin\ClientUsingGuzzle;
use App\Services\DirectAdmin\Contracts\Client;
use App\Services\DirectAdmin\Contracts\EmailListAdapter as EmailListAdapterContract;
use App\Services\DirectAdmin\EmailListAdapter;
use App\Services\DirectAdmin\ValueObjects\EmailList;
use App\Services\EmailListUpdater\EmailListUpdater;
use App\Services\EmailListUpdater\EmailListUpdaterUsingDirectAdmin;
use App\Services\WebsiteUpdater\WebsiteUpdater;
use App\Services\WebsiteUpdater\WebsiteUpdaterThroughWebhook;
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
            QueueUpdateEmailListSubscriptions::class,
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

    public function register(): void
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
            return new WebsiteUpdaterThroughWebhook(
                new HttpClient(),
                config('website.webhook.uri'),
            );
        });

        $this->app->bind(EmailListUpdater::class, function (Application $app) {
            return new EmailListUpdaterUsingDirectAdmin(
                new EmailList(
                    config('emaillist.directadmin.name'),
                    config('emaillist.directadmin.domain'),
                ),
                $app->get(EmailListAdapter::class),
                config('emaillist.lists.all.memberTypes'),
            );
        });
        $this->app->bind(EmailListAdapterContract::class, EmailListAdapter::class);
        $this->app->bind(Client::class, function () {
            return new ClientUsingGuzzle(
                config('emaillist.directadmin.username'),
                config('emaillist.directadmin.accesstoken'),
                new HttpClient([
                    'base_uri' => config('emaillist.directadmin.uri'),
                ]),
            );
        });
    }
}
