<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Helpers\Payment\MolliePaymentProvider;

class Mollie extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MolliePaymentProvider::class;
    }
}
