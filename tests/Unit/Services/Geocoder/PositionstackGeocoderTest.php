<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\Geocoder\PositionstackGeocoder;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class PositionstackGeocoderTest extends TestCase
{

    private $geocoder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->geocoder = new PositionstackGeocoder();
    }

    public function test_geocode_returns_geolocation()
    {

        $fakeBody = [
            'data' => [
                [
                    'latitude' => 14,
                    'longitude' => 73
                ]
            ]
        ];

        Http::fake(Http::response(json_encode($fakeBody)));

        $geo = $this->geocoder->geocode('some address');

        $this->assertEquals(14, $geo->latitude);
        $this->assertEquals(73, $geo->longitude);
    }

    public function test_geocode_failed_request_throws_exception()
    {
        Http::fake(Http::response(null, 500));

        $this->expectException(RequestException::class);
        $this->geocoder->geocode('some address');
    }

    public function test_geocode_no_match_throws_exception()
    {
        $fakeBody = [
            'data' => []
        ];

        Http::fake(Http::response(json_encode($fakeBody)));

        $this->expectException(\UnexpectedValueException::class);
        $this->geocoder->geocode('some address');
    }
}
