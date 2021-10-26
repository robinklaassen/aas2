<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class BasicTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
