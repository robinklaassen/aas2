<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;

class MemberProfileOnCampTest extends TestCase
{
    use WithoutMiddleware;

    private $member_id = 3;
    private $event_id = 2;

    private $member;
    private $event;

    private function clearDB(): void
    {
        $this->member->events()->detach();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = \App\Member::findOrFail($this->member_id);
        $this->event = \App\Event::findOrFail($this->event_id);

        $this->clearDB();
    }

    protected function tearDown(): void
    {
        $this->clearDB();
        parent::tearDown();
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
