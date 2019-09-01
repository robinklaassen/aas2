<?php

namespace Tests\Feature;

use Tests\TestCase;

class ParticipantTest extends TestCase
{
    public $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = \App\User::findOrFail(1);
    }

    /**
     * Test the participants index page
     *
     * @return void
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
     * Test the show participant page
     *
     * @return void
     */
    public function testShow()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/participants/1')
            ->assertStatus(200)
            ->assertSee('Bewerken', 'Elst', 'Meikamp');
    }
}
