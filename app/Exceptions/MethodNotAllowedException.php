<?php

declare(strict_types=1);

namespace App\Exceptions;

class MethodNotAllowedException extends \BadMethodCallException
{
    public function __construct(string $msg)
    {
        parent::__construct($msg);
    }
}
