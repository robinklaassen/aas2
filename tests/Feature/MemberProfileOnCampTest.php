<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;

class MemberProfileOnCampTest extends TestCase
{
    use WithoutMiddleware;
    use DatabaseTransactions;

    private $member_id = 3;
    private $event_id = 2;

    private $member;
    private $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = \App\Member::findOrFail($this->member_id);
        $this->event = \App\Event::findOrFail($this->event_id);
    }

    /**
     * Tests to send the member on camp from the profile page
     *
     * @return void
     */
    public function testOnCamp()
    {
        Mail::fake();

        $user = $this->member->user;
        $this->assertNotNull($user);

        $response = $this->actingAs($user)->put(action("ProfileController@onCampSave"), [
            'selected_camp' => $this->event_id
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(action('ProfileController@show'));
        $response->assertSessionHas("flash_message", 'Je gaat op kamp!');
    }
}
