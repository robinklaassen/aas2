<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ParticipantTest extends TestCase
{
    public $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = \App\Models\User::findOrFail(1);
    }

    /**
     * Test the participants index page
     */
    public function testIndex()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/participants')
            ->assertStatus(200)
            ->assertSee('Annabelle', 'Jaap', '/participants/1');
    }

    /**
     * Test the show participant page for an unauthorized (normal member)
     */
    public function testShowUnauthorized()
    {
        $response = $this
            ->actingAs(\App\Models\User::findOrFail(4)) // normal member
            ->get('/participants/1')
            ->assertStatus(403);
    }

    /**
     * Test the show participant page
     */
    public function testShow()
    {
        $response = $this
            ->actingAs(\App\Models\User::findOrFail(6)) // kampci
            ->get('/participants/1')
            ->assertStatus(200)
            ->assertSee('Elst', 'Meikamp')
            ->assertDontSee('Bewerken'); // doesn't have edit right
    }

    public function testProfile()
    {
        $response = $this
            ->actingAs(\App\Models\User::where([
                'username' => 'henk',
            ])->firstOrFail())
            ->get('/profile')
            ->assertStatus(200)
            ->assertSee('Bewerken')
            ->assertSee('Op kamp')
            ->assertSee('Nieuw wachtwoord');
    }
}
