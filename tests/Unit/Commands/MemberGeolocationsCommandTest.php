<?php

namespace Tests\Unit\Commands;

use App\Jobs\UpdateMemberGeolocation;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MemberGeolocationsCommandTest extends TestCase
{
    public function testCommandDispatchesJobs()
    {
        $this->expectsJobs(UpdateMemberGeolocation::class);
        Artisan::call('member:geolocations');
    }
}
