<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EventTest extends TestCase
{
    use DatabaseTransactions;

    public const CONF_USER_ID = 11;

    public function testPhoneIsVisibleForMembers()
    {

        // dkrijgsman a normal member
        $user = User::findOrFail(4);

        // normal members see the phone number, the anderwijs email but not the private email
        $this->actingAs($user)
            ->get('/members/' . self::CONF_USER_ID)
            ->assertStatus(200)
            ->assertSee('0611211211')
            ->assertSee('siep@anderwijs.nl')
            ->assertDontSee('siep@heeljong.nl');
    }

    public function testPhoneIsNotVisibleForParticipants()
    {

        // henk: a participant
        $user = User::findOrFail(12);

        // members should not see the member at all
        $resp = $this->actingAs($user)
            ->get('/members/' . self::CONF_USER_ID)
            ->assertStatus(403);

        // dd($resp->getContent());
    }
}
