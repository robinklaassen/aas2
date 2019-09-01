<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class MemberRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    private $fakeMemberData = [
        'voornaam' => 'Berend',
        'tussenvoegsel' => 'van',
        'achternaam' => 'Zuid',
        'geboortedatum' => '1980-10-10',
        'geslacht' => 'M',
        'adres' => 'Boegmeen 27',
        'postcode' => '1234 AB',
        'plaats' => 'Lalastad',
        'telefoon' => '0123456789',
        'email' => 'berend@vanzuid.nl',
        'selected_camp' => '1',
        'eindexamen' => 'VWO',
        'studie' => 'Nietsnuttigheid',
        'afgestudeerd' => 1,
        'vak1' => 1,
        'klas1' => 1,
        'hoebij' => ['je moeder', 'je vader'],
        'vog' => 1,
        'privacy' => 1
    ];

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
        // POST and check status code
        $response = $this
            ->followingRedirects()
            ->post('/register-member', $this->fakeMemberData);

        $response->assertStatus(200);

        // Check existence of member and user in DB
        $this->assertDatabaseHas('members', [
            'email' => $this->fakeMemberData['email']
        ]);

        $this->assertDatabaseHas('users', [
            'username' => 'bzuid'
        ]);

        $this->assertDatabaseHas('event_member', [
            'member_id' => \App\Member::where('email', $this->fakeMemberData['email'])->first()->id,
            'event_id' => 1
        ]);
    }
}
