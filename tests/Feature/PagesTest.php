<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class PagesTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = \App\User::findOrFail(1);
    }

    /**
     * Test the lists page
     */
    public function testListsPage()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/lists')
            ->assertStatus(200);
    }

    /**
     * Test the graphs page
     */
    public function testGraphsPage()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/graphs')
            ->assertStatus(200);
    }
}
