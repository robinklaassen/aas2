<?php

namespace App\Exceptions;

use Exception;

class GeocoderException extends Exception
{
    public function __construct(string $msg)
    {
        parent::__construct($msg);
    }
}
