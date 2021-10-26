<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Bus\Dispatcher;
use Illuminate\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(Dispatcher $dispatcher)
    {
        // $dispatcher->mapUsing(function($command)
        // {
        // 	return Dispatcher::simpleMapping(
        // 		$command, 'App\Commands', 'App\Handlers\Commands'
        // 	);
        // });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }
}
