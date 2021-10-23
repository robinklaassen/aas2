<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EventCancelTest extends TestCase
{
    use DatabaseTransactions;

    public function testKampcieCanCancelEvents()
    {
        $user = User::findOrFail(6); // kampci
        
        $this->actingAs($user)
            ->followingRedirects()
            ->patch('events/1', ['cancelled' => '1'])
            ->assertStatus(200);
    }

    public function testNormalMemberCannotCancelEvents()
    {
        $user = User::findOrFail(4); // dkrijgsman, normal user

        $this->actingAs($user)
            ->followingRedirects()
            ->patch('events/1', ['cancelled' => '1'])
            ->assertStatus(403);
    }
}
