<?php

declare(strict_types=1);

namespace App\Services\Geocoder;

use Illuminate\Support\Facades\Http;

class PositionstackGeocoder implements GeocoderInterface {
    public function geocode(string $address): ?array {
        $response = Http::get('http://api.positionstack.com/v1/forward', [
            'access_key' => config('positionstack.api_key'),
            'query' => $address,
            'country' => 'NL',  // TODO allow overriding?
            'limit' => 1
        ]);

        if ($response->successful()) {
            return $response->json()['data'][0];
        }

        return array();

    }
}