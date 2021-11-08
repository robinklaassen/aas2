<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class MemberTest extends TestCase
{
    public $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::findOrFail(1);
    }

    /**
     * Test the members index page
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
     */
    public function testShow()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/members/3')
            ->assertStatus(200)
            ->assertSee('Bewerken', 'Flipstoeje', 'Lijst met acties', 'Natuurkunde');
    }

    public function testMap()
    {
        $this->withoutJobs();
        $response = $this
            ->actingAs($this->user)
            ->get('/members/map')
            ->assertStatus(200);
    }
}
