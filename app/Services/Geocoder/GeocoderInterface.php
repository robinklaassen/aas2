<?php

declare(strict_types=1);

namespace App\Services\Geocoder;

interface GeocoderInterface {
    public function geocode(string $address): ?array;
}
