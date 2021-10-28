<?php

declare(strict_types=1);

namespace App\Exceptions;

class UnexpectedInstance extends \UnexpectedValueException
{
    public function __construct(string $class, $instance)
    {
        parent::__construct("Unexpected instance. Expected ${class}, got " . get_class($instance));
    }
}
