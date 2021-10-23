<?php

namespace App\Facades;

use App\Helpers\Payment\MolliePaymentProvider;
use Illuminate\Support\Facades\Facade;

class Mollie extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MolliePaymentProvider::class;
    }
}
