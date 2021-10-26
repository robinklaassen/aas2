<?php

declare(strict_types=1);

namespace Tests\Unit\Listeners;

use App\Jobs\UpdateMemberGeolocation;
use App\Member;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

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
