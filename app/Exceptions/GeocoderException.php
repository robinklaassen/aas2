<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class GeocoderException extends Exception
{
    public function __construct(string $msg)
    {
        parent::__construct($msg);
    }
}
