<?php

namespace Tests\Integration;

use App\Services\Geocoder\PositionstackGeocoder;
use Tests\TestCase;

class PositionstackGeocoderTest extends TestCase
{
    public function testGeocode()
    {
        $geocoder = new PositionstackGeocoder();
        
        $test_address = 'Putterweg 2, 3886 PC Garderen';  // IJssalon IJstijd Garderen, altijd goed!
        $geo = $geocoder->geocode($test_address);

        $this->assertEquals(52.23, round($geo->latitude, 2));
        $this->assertEquals(5.71, round($geo->longitude, 2));
    }
}
