<?php

namespace App\Data;

use Grimzy\LaravelMysqlSpatial\Types\Point;

/**
 * Value object for a geolocation (point).
 */
class Geolocation 
{
    public float $latitude;
    public float $longitude;
    
    public function __construct(float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function toPoint(): Point {
        return new Point($this->latitude, $this->longitude);
    }
}