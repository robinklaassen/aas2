<?php

namespace Tests\Feature;

use Tests\TestCase;

class EventTest extends TestCase
{
    public $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = \App\User::findOrFail(1);
    }

    /**
     * Test the events index page
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/events')
            ->assertStatus(200)
            ->assertSee('Nieuwjaarskamp')
            ->assertSee('Training Meikamp');
    }

    /**
     * Test the show event page
     *
     * @return void
     */
    public function testShow()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/events/1')
            ->assertStatus(200)
            ->assertSeeInOrder(['Bewerken', 'Venlo', 'Ranonkeltje', 'Vakdekking', 'Annabelle']);
    }
}
