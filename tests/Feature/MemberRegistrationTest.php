<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Events\MemberUpdated;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MemberRegistrationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var (string|int|array) array
     */
    private $postData = [
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
        'selected_camp' => 1,
        'eindexamen' => 'VWO',
        'studie' => 'Nietsnuttigheid',
        'afgestudeerd' => 1,
        'vak1' => 1,
        'klas1' => 1,
        'hoebij' => ['je moeder', 'je vader'],
        'vog' => 1,
        'privacy' => 1,
    ];

    private $memberData;

    private $userData;

    protected function setUp(): void
    {
        $this->markTestSkipped('Registration pages are disabled.');

        parent::setUp();

        // Create member data to test for in DB
        $this->memberData = $this->postData;
        $keysToRemove = ['selected_camp', 'vak1', 'klas1', 'vog', 'privacy'];
        foreach ($keysToRemove as $key) {
            unset($this->memberData[$key]);
        }
        $this->memberData['hoebij'] = implode(', ', $this->postData['hoebij']);

        $this->userData = [
            'username' => 'bzuid',
            'is_admin' => 0,
        ];

        Event::fake([MemberUpdated::class]);
    }

    /**
     * Test that the member registration form opens correctly.
     */
    public function testMemberRegistrationForm()
    {
        $response = $this
            ->get('/register-member')
            ->assertStatus(200);
    }

    /**
     * Test that the member registration request is handled properly.
     */
    public function testMemberRegistrationHandler()
    {
        Mail::fake();

        // POST and check status code
        $response = $this
            ->followingRedirects()
            ->post('/register-member', $this->postData)
            ->assertStatus(200);

        // Check existence of member and user in DB
        $this->assertDatabaseHas('members', $this->memberData);
        $this->assertDatabaseHas('users', $this->userData);
        $this->assertDatabaseHas('event_member', [
            'member_id' => \App\Models\Member::latest()->first()->id,
            'event_id' => $this->postData['selected_camp'],
        ]);
        Event::assertDispatched(MemberUpdated::class);
    }
}
