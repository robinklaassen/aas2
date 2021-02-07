<?php

namespace Updater;

use Illuminate\Support\ServiceProvider;
use Updater\Services\CommandExecutor\ArtisanExecutor;
use Updater\Services\CommandExecutor\ComposerExecutor;
use Updater\Services\SourceControl\GitSourceControlService;
use Updater\Services\Updater\UpdaterService;
use Updater\Services\Updater\UpdaterServiceInterface;
use Updater\Subscribers\ArtisanPostUpdateSubscriber;
use Updater\Subscribers\ComposerPostUpdateSubscriber;

class UpdaterServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        ComposerPostUpdateSubscriber::class,
        ArtisanPostUpdateSubscriber::class
    ];

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/updater.php' => config_path('updater.php'),
        ]);

        $this->app->bind(UpdaterServiceInterface::class, function ($app) {
            return new UpdaterService(
                $app->make(GitSourceControlService::class),
                $app->make(ArtisanExecutor::class),
                config('updater.git.remote'),
                config('updater.git.branch')
            );
        });
        $this->app->bind(ComposerPostUpdateSubscriber::class, function ($app) {
            return new ComposerPostUpdateSubscriber(
                new ComposerExecutor(config('updater.composer'))
            );
        });
    }
}
