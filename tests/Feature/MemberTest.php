<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class MemberTest extends TestCase
{
    public $admin;

    public $member;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::findOrFail(1); // Ranonkeltje
        $this->member = User::findOrFail(4); // dkrijgsman
    }

    /**
     * Test the members index page
     */
    public function testIndex()
    {
        $response = $this
            ->actingAs($this->admin)
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
            ->actingAs($this->admin)
            ->get('/members/3')
            ->assertStatus(200)
            ->assertSee('Bewerken', 'Flipstoeje', 'Lijst met acties', 'Natuurkunde');
    }

    public function testMap()
    {
        Queue::fake();
        $response = $this
            ->actingAs($this->admin)
            ->get('/members/map')
            ->assertStatus(200);
    }

    public function testProfile()
    {
        $this
            ->actingAs($this->member)
            ->get('/profile')
            ->assertStatus(200)
            ->assertSee('Dingo Krijgsman')
            ->assertSee('Bewerken')
            ->assertSee('Op kamp')
            ->assertSee('Declaraties')
            ->assertSee('Nieuw wachtwoord');
    }
}
