<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Member;
use App\Jobs\UpdateMemberGeolocation;

class MemberTest extends TestCase
{
    public function testGeolocatieAccessorDispatchesJob()  // TODO this test fails and I'm not sure why
    {
        $member = Member::findOrFail(1);
        $this->expectsJobs(UpdateMemberGeolocation::class);
        $something = $member->geolocatie;
    }
}
