<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class RolesTest extends TestCase
{
    public $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = \App\User::findOrFail(1);
    }

    public function testIndex()
    {
        $this
            ->actingAs($this->user)
            ->get('/roles/explain')
            ->assertStatus(200)
            ->assertSee('Aasbaas', 'Deelnemersinfo - Inzien');
    }

    public function testShowUnauthorized()
    {
        $this
            ->get('/roles/explain')
            ->assertStatus(302);
    }
}
