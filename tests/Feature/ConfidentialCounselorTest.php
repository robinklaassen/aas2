<?php

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EventTest extends TestCase
{
    use DatabaseTransactions;

    const CONF_USER_ID = 11;

    public function testPhoneIsVisibleForMembers() {

        // dkrijgsman a normal member
        $user = User::findOrFail(4);

        // normal members see the phone number, the anderwijs email but not the private email
        $this->actingAs($user)
            ->get("/members/" . EventTest::CONF_USER_ID)
            ->assertStatus(200)
            ->assertSee("0611211211")
            ->assertSee("siep@anderwijs.nl")
            ->assertDontSee("siep@heeljong.nl");

    }

    public function testPhoneIsNotVisibleForParticipants() {

        // annabel a participant
        $user = User::findOrFail(3);

        // members should not see the member at all
        $this->actingAs($user)
            ->get("/members/" . EventTest::CONF_USER_ID)
            ->assertStatus(403);
    }


}