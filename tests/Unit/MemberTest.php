<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Member;
use App\Jobs\UpdateMemberGeolocation;

class MemberTest extends TestCase
{
    public function testGeolocatieAccessorDispatchesJob()
    {
        $member = Member::findOrFail(1);
        $this->expectsJobs(UpdateMemberGeolocation::class);  // TODO this fails, probably because dispatchSync doesn't actually get sent to the Bus
        $something = $member->geolocatie;
    }
}
