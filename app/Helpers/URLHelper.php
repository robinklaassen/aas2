<?php

namespace App\Helpers;

use Illuminate\Support\Facades\URL;

class URLHelper
{
    public static function isProfile(): bool
    {
        return (strpos(URL::current(), 'profile') !== false);
    }
}
