<?php

namespace Tests\Unit\Jobs;

use App\Data\Geolocation;
use Tests\TestCase;
use App\Jobs\UpdateMemberGeolocation;
use App\Member;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use App\Services\Geocoder\GeocoderInterface;

class UpdateMemberGeolocationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_update_member_geolocation()
    {
        $member = new Member();
        $fakeLocation = new Geolocation(10.0, 20.0);

        $geocoder = Mockery::mock(GeocoderInterface::class, [
            'geocode' => $fakeLocation
        ]);

        $job = new UpdateMemberGeolocation($member);
        $job->handle($geocoder);

        $this->assertEquals(10.0, $member->geolocatie->getLat());
        $this->assertEquals(20.0, $member->geolocatie->getLng());
    }
}
