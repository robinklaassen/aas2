<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Data\Geolocation;
use App\Jobs\UpdateMemberGeolocation;
use App\Member;
use App\Services\Geocoder\GeocoderInterface;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;
use Tests\TestCase;

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

        $this->assertSame(10.0, $member->geolocatie->getLat());
        $this->assertSame(20.0, $member->geolocatie->getLng());
    }
}
