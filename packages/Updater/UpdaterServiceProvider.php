<?php

namespace Updater;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Updater\Events\PostUpdateEvent;
use Updater\Events\PreUpdateEvent;
use Updater\Listeners\ArtisanPreUpdateListener;
use Updater\OutputAggregator\InternalOutputRecorder;
use Updater\OutputAggregator\OutputRecorderInterface;
use Updater\Services\CommandExecutor\ArtisanExecutor;
use Updater\Services\CommandExecutor\ArtisanExecutorInterface;
use Updater\Services\CommandExecutor\ComposerExecutor;
use Updater\Services\CommandExecutor\ComposerExecutorInterface;
use Updater\Services\CommandExecutor\ExecutorInterface;
use Updater\Services\CommandExecutor\RecordedExecutor;
use Updater\Services\CommandExecutor\ShellExecutor;
use Updater\Services\DateTimeProvider;
use Updater\Services\DateTimeProviderInterface;
use Updater\Services\SourceControl\GitSourceControlService;
use Updater\Services\Updater\UpdaterService;
use Updater\Services\Updater\UpdaterServiceInterface;
use Updater\Listeners\ArtisanPostUpdateListener;
use Updater\Listeners\ComposerPostUpdateListener;

class UpdaterServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/updater.php' => config_path('updater.php'),
        ], 'updater.config');

        $this->bindDependancies();

        Event::listen(PreUpdateEvent::class, ArtisanPreUpdateListener::class);
        Event::listen(PostUpdateEvent::class, ComposerPostUpdateListener::class);
        Event::listen(PostUpdateEvent::class, ArtisanPostUpdateListener::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/updater.php', 'updater');
    }

    private function bindDependancies()
    {

        $this->app->singleton(OutputRecorderInterface::class, InternalOutputRecorder::class);
        $this->app->bind(DateTimeProviderInterface::class, DateTimeProvider::class);

        $this->bindExecutors();

        $this->app->when(ComposerPostUpdateListener::class)
            ->needs(ExecutorInterface::class)
            ->give(function ($app) {
                return $app->make('executor.recorded.composer');
            });

        $this->app->when([ArtisanPreUpdateListener::class, ArtisanPostUpdateListener::class])
            ->needs(ExecutorInterface::class)
            ->give(function ($app) {
                return $app->make('executor.recorded.artisan');
            });

        $this->app->bind(GitSourceControlService::class, function (Application $app) {
            return new GitSourceControlService(
                $app->make('executor.shell'),
                $app->make('executor.recorded.shell')
            );
        });

        $this->app->bind(UpdaterServiceInterface::class, function (Application $app) {
            return new UpdaterService(
                $app->make(GitSourceControlService::class),
                $app->make(OutputRecorderInterface::class),
                $app->make('executor.recorded.artisan'),
                config('updater.git.remote'),
                config('updater.git.branch')
            );
        });

        $this->app->when(ComposerExecutor::class)
            ->needs('$composer')
            ->give(config('updater.composer_path'));

    }

    private function bindExecutors() {
        $executors = [
            'composer' => ComposerExecutor::class,
            'shell' => ShellExecutor::class,
            'artisan' => ArtisanExecutor::class,
        ];
        foreach ($executors as $key => $concrete) {
            $this->app->bind("executor.$key", $concrete);
            $this->app->bind("executor.recorded.$key", function (Application $app) use ($key) {
                return new RecordedExecutor(
                    $app->make("executor.$key"),
                    $app->make(OutputRecorderInterface::class),
                );
            });
        }
    }
}
