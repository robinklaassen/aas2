<?php

declare(strict_types=1);

namespace Tests\Unit\Listeners;

use App\Jobs\UpdateMemberGeolocation;
use App\Models\Member;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueueMemberGeolocationTest extends TestCase
{
    use DatabaseTransactions;

    private $member;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
        $this->member = Member::findOrFail(1);  // Ranonkeltje
    }

    public function testAddressChangeDispatchesJob()
    {
        $this->member->plaats = 'Bla';
        $this->member->save();

        Queue::assertPushed(UpdateMemberGeolocation::class);
    }

    public function testOtherChangeDoesNotDispatchJob()
    {
        $this->member->incasso = 0;
        $this->member->save();

        Queue::assertNotPushed(UpdateMemberGeolocation::class);
    }
}
