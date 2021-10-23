<?php

declare(strict_types=1);

namespace App\Services\Geocoder;

use App\Data\Geolocation;
use App\Exceptions\GeocoderException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

/**
 * Geocoder based on the Positionstack API.
 * See https://positionstack.com/documentation for docs.
 */
class PositionstackGeocoder implements GeocoderInterface
{
    public const HTTP_TIMEOUT_SECONDS = 4;

    public function geocode(string $address, string $country = 'NL'): Geolocation
    {
        try {
            $response = Http::timeout($this::HTTP_TIMEOUT_SECONDS)->get('http://api.positionstack.com/v1/forward', [
                'access_key' => config('positionstack.api_key'),
                'query' => $address,
                'country' => $country,
                'limit' => 1
            ]);
        } catch (ConnectionException $e) {
            throw new GeocoderException("HTTP connection timeout during geocoding.");
        }

        if ($response->failed()) {
            throw new GeocoderException("HTTP request failed during geocoding (status code {$response->status()}, message '{$response->body()}'.");
        }

        $responseData = $response->json()['data'];
        if (!$responseData) {  // empty array when geolocation cannot be found
            throw new GeocoderException("No geolocation match found for address '$address'.");
        }

        $match = $responseData[0];

        return new Geolocation($match['latitude'], $match['longitude']);
    }
}
