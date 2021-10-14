<?php

namespace Tests\Unit\Jobs;

use Mockery;
use Tests\TestCase;
use App\Member;
use App\Data\Geolocation;
use App\Jobs\UpdateMemberGeolocation;
use App\Services\Geocoder\GeocoderInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateMemberGeolocationTest extends TestCase
{
    use DatabaseTransactions;

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    public function testJobHandlesCorrectly()
    {
        $member = new Member();
        $fakeLocation = new Geolocation(10.0, 20.0);

        $geocoder = Mockery::mock(GeocoderInterface::class);
        $geocoder->expects('geocode')->andReturns($fakeLocation);

        $job = new UpdateMemberGeolocation($member);
        $job->handle($geocoder);

        $member->refresh();

        $this->assertEquals(10.0, $member->geolocatie->getLat());
        $this->assertEquals(20.0, $member->geolocatie->getLng());
    }
}
