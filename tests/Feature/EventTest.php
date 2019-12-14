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
            ->assertSee('Nieuwjaarskamp', 'Training Meikamp', '/events/1');
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
            ->assertSee('Bewerken', 'Venlo', 'Ranonkeltje', 'Vakdekking', 'Annabelle');
    }

    public function testExport()
    {
        // exports  event 1, it has participants with and without courses
        $response = $this
            ->actingAs($this->user)
            ->get('/events/1/export')
            ->assertStatus(200)
            ->assertHeader("Content-Type", "application/pdf");
    }
}
