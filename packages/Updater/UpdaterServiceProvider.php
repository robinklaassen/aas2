<?php

namespace Updater;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Updater\Services\CommandExecutor\ArtisanExecutor;
use Updater\Services\CommandExecutor\ComposerExecutor;
use Updater\Services\CommandExecutor\ShellExecutor;
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
        ], 'updater.config');

        $this->app->bind(GitSourceControlService::class, function (Application $app) {
            return new GitSourceControlService(
                $app->make(ShellExecutor::class)
            );
        });
        $this->app->bind(UpdaterServiceInterface::class, function (Application $app) {
            return new UpdaterService(
                $app->make(GitSourceControlService::class),
                $app->make(ArtisanExecutor::class),
                config('updater.git.remote'),
                config('updater.git.branch')
            );
        });
        $this->app->bind(ComposerPostUpdateSubscriber::class, function (Application $app) {
            return new ComposerExecutor(
                config('updater.composer_path')
            );
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/updater.php', 'updater');
    }
}
