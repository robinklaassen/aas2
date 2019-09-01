<?php

namespace Tests\Feature;

use Tests\TestCase;

class MemberTest extends TestCase
{
    public $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = \App\User::findOrFail(1);
    }

    /**
     * Test the members index page
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/members')
            ->assertStatus(200)
            ->assertSee('Dingo', 'Ranonkeltje', '/members/1');
    }

    /**
     * Test the show member page
     *
     * @return void
     */
    public function testShow()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/members/3')
            ->assertStatus(200)
            ->assertSee('Bewerken', 'Flipstoeje', 'Lijst met acties', 'Natuurkunde');
    }
}
