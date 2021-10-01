<?php

namespace App\Data;

/**
 * Utility class for a geolocation (point).
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

    public function toPoint($self): string {
        return sprintf('POINT(%f, %f)', $self->latitude, $self->longitude);
    }
}