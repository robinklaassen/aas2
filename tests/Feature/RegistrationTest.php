<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{

    //use RefreshDatabase; TODO this would be nice to use but throws an error about the Mockery class

    /**
     * Test that the member registration form opens correctly.
     *
     * @return void
     */
    public function testMemberRegistrationForm()
    {
        $response = $this->get('/register-member');
        $response->assertStatus(200);
    }

    /**
     * Test that the member registration request is handled properly.
     */
    public function testMemberRegistrationHandler()
    {
        $fakeEmail = 'berend@vanzuid.nl';

        // Delete any members with this email
        \App\Member::where('email', $fakeEmail)->delete();

        // POST and check status code
        $response = $this
            ->followingRedirects()
            ->post('/register-member', [
                'voornaam' => 'Berend',
                'tussenvoegsel' => 'van',
                'achternaam' => 'Zuid',
                'geboortedatum' => '1980-10-10',
                'geslacht' => 'M',
                'adres' => 'Boegmeen 27',
                'postcode' => '1234 AB',
                'plaats' => 'Lalastad',
                'telefoon' => '0123456789',
                'email' => $fakeEmail,
                'selected_camp' => '1',
                'eindexamen' => 'VWO',
                'studie' => 'Nietsnuttigheid',
                'afgestudeerd' => 1,
                'vak1' => 1,
                'klas1' => 1,
                'hoebij' => ['je moeder', 'je vader'],
                'vog' => 1,
                'privacy' => 1
            ]);

        $response->assertStatus(200);

        // Check existence of member and user in DB
        $this->assertDatabaseHas('members', [
            'email' => $fakeEmail
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'bzuid'
        ]);
    }
}
