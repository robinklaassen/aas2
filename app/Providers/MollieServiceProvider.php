<?php

declare(strict_types=1);

namespace App\Providers;

use App\Helpers\Payment\MolliePaymentProvider;
use App\Helpers\Payment\PaymentProvider;
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
        $this->app->bind(PaymentProvider::class, MolliePaymentProvider::class);
    }
}
