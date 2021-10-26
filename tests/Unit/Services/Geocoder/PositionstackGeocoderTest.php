<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Exceptions\GeocoderException;
use App\Services\Geocoder\PositionstackGeocoder;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PositionstackGeocoderTest extends TestCase
{
    private $geocoder;

    protected function setUp(): void
    {
        parent::setUp();

        $this->geocoder = new PositionstackGeocoder();
    }

    public function testGeocodeReturnsGeolocation()
    {
        $fakeBody = [
            'data' => [
                [
                    'latitude' => 14,
                    'longitude' => 73,
                ],
            ],
        ];

        Http::fake(Http::response(json_encode($fakeBody)));

        $geo = $this->geocoder->geocode('some address');

        $this->assertSame(14, $geo->latitude);
        $this->assertSame(73, $geo->longitude);
    }

    public function testGeocodeFailedRequestThrowsException()
    {
        Http::fake(Http::response(null, 500));

        $this->expectException(GeocoderException::class);
        $this->geocoder->geocode('some address');
    }

    public function testGeocodeNoMatchThrowsException()
    {
        $fakeBody = [
            'data' => [],
        ];

        Http::fake(Http::response(json_encode($fakeBody)));

        $this->expectException(GeocoderException::class);
        $this->geocoder->geocode('some address');
    }
}
