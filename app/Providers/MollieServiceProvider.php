<?php

declare(strict_types=1);

namespace App\Providers;

use App\Helpers\Payment\MolliePaymentProvider;
use Illuminate\Support\ServiceProvider;

class MollieServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->bind('MolliePaymentProvider', function ($app) {
            return new MolliePaymentProvider();
        });
    }
}
