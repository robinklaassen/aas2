<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * Test the login functionality
     */
    public function testLogin()
    {
        $response = $this
            ->followingRedirects()
            ->post('/login', [
                'username' => 'ranonkeltje',
                'password' => 'ranonkeltje',
            ])
            ->assertStatus(200);

        $user = \App\User::findOrFail(1);
        $this->assertAuthenticatedAs($user);
    }
}
