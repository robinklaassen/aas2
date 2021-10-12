<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use App\Jobs\UpdateMemberGeolocation;
use Illuminate\Support\Facades\Artisan;

class MemberGeolocationsCommandTest extends TestCase
{
    public function testCommandDispatchesJobs()
    {
        $this->expectsJobs(UpdateMemberGeolocation::class);
        Artisan::call('member:geolocations');
    }
}
