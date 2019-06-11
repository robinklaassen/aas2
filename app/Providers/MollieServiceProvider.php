<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use \App\Helpers\Payment\MolliePaymentProvider;

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
        $this->app->bind('MolliePaymentProvider', function ($app) {
            return new MolliePaymentProvider;
        });
    }
}
