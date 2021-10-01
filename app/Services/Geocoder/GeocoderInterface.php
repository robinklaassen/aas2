<?php

declare(strict_types=1);

namespace App\Services\Geocoder;

use App\Data\Geolocation;

interface GeocoderInterface {
    public function geocode(string $address): Geolocation;
}
