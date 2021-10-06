<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Member;
use App\Jobs\UpdateMemberGeolocation;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QueueMemberGeolocationTest extends TestCase
{
    use DatabaseTransactions;

    private $member;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = Member::findOrFail(1);  // Ranonkeltje
    }

    public function testAddressChangeDispatchesJob()
    {
        $this->expectsJobs(UpdateMemberGeolocation::class);
        
        $this->member->plaats = 'Bla';
        $this->member->save();
    }

    public function testOtherChangeDoesNotDispatchJob()
    {
        $this->doesntExpectJobs(UpdateMemberGeolocation::class);

        $this->member->incasso = 0;
        $this->member->save();
    }
}