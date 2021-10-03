<?php

namespace App\Data;

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

    public function toPoint(): string {
        return sprintf('POINT(%f, %f)', $this->latitude, $this->longitude);
    }
}