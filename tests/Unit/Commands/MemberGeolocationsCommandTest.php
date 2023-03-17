<?php

declare(strict_types=1);

namespace Tests\Unit\Commands;

use App\Jobs\UpdateMemberGeolocation;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MemberGeolocationsCommandTest extends TestCase
{
    public function testCommandDispatchesJobs()
    {
        Queue::fake();
        Artisan::call('member:geolocations');
        Queue::assertPushed(UpdateMemberGeolocation::class);
    }
}
