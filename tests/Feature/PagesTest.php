<?php

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
     *
     * @return void
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
     *
     * @return void
     */
    public function testGraphsPage()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/graphs');
        dd($response->getContent());
            //->assertStatus(200);
    }
}
