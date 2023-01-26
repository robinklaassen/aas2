<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class PrivacyStatementTest extends TestCase
{
    private const PRIVACY_FORM_TEXT = ['Ik geef Anderwijs toestemming', 'Opslaan'];

    /**
     * Test the privacy page while not logged in
     */
    public function testPrivacyGuest()
    {
        $response = $this
            ->get('/privacy')
            ->assertStatus(200);
    }

    /**
     * Test the privacy page while logged in and not accepted yet
     */
    public function testPrivacyUserUnaccepted()
    {
        $user = User::findOrFail(3); // annabelle

        $response = $this
            ->actingAs($user)
            ->get('/privacy')
            ->assertRedirectContains('accept-privacy');

        $response = $this
            ->actingAs($user)
            ->get('/accept-privacy')
            ->assertStatus(200)
            ->assertSee($this::PRIVACY_FORM_TEXT);
    }

    /**
     * Test the privacy page while logged in and accepted
     */
    public function testPrivacyUserAccepted()
    {
        $user = User::findOrFail(1); // ranonkeltje

        $response = $this
            ->actingAs($user)
            ->get('/privacy')
            ->assertStatus(200)
            ->assertDontSee($this::PRIVACY_FORM_TEXT);
    }
}
