<?php


namespace App\Exceptions;

class UnexpectedInstance extends \UnexpectedValueException
{
    public function __construct(string $class, $instance)
    {
        parent::__construct("Unexpected instance. Expected ${class}, got " . get_class($instance));
    }
}
