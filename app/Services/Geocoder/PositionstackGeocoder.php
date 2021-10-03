<?php

declare(strict_types=1);

namespace App\Services\Geocoder;

use App\Data\Geolocation;
use Illuminate\Support\Facades\Http;

/**
 * Geocoder based on the Positionstack API.
 * See https://positionstack.com/documentation for docs.
 */
class PositionstackGeocoder implements GeocoderInterface {

    public function geocode(string $address): Geolocation {
        $response = Http::get('http://api.positionstack.com/v1/forward', [
            'access_key' => config('positionstack.api_key'),
            'query' => $address,
            'country' => 'NL',  // TODO allow overriding?
            'limit' => 1
        ]);

        $response->throw();  // throws Illuminate\Http\Client\RequestException if request failed

        $responseData = $response->json()['data'];
        if (!$responseData) {  // empty array when geolocation cannot be found
            throw new \UnexpectedValueException("No geolocation match found for address '$address'");
        }

        $match = $responseData[0];

        return new Geolocation($match['latitude'], $match['longitude']);
    }
}
