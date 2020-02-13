<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function Format(Carbon $date)
    {
        return $date !== null ? $date->format('d-m-Y') : null;
    }
}
