<?php

namespace Tests\Unit\Models;

use App\Event;
use App\Member;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MemberTest extends TestCase
{
    use DatabaseTransactions;

    private $member;
    private $camp;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = Member::findOrFail(1);  // Ranonkeltje
        $this->camp = Event::findOrFail(5);  // Nieuwjaarskamp 2090
    }

    public function testGetNextCampReturnsEvent()
    {
        $this->member->events()->sync([$this->camp->id]);
        $nextCamp = $this->member->getNextFutureCamp();
        $this->assertEquals($this->camp->id, $nextCamp->id);
    }

    public function testGetNextCampReturnsNull()
    {
        $this->assertNull($this->member->getNextFutureCamp());
    }
}
