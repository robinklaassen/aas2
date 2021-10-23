<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public const FORMAT = 'd-m-Y';
    public static function Format($date)
    {
        if ($date instanceof Carbon) {
            return $date->format(DateHelper::FORMAT);
        }
        return null;
    }
}
