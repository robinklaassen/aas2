<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\Helpers\MollieWrapper;

class MollieServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('MollieWrapper', function($app) {
            return new MollieWrapper;
        });
    }
}
