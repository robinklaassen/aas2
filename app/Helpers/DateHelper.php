<?php

declare(strict_types=1);

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public const FORMAT = 'd-m-Y';

    public static function Format($date)
    {
        if ($date instanceof Carbon) {
            return $date->format(self::FORMAT);
        }
        return null;
    }
}
