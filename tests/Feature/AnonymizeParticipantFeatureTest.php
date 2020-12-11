<?php

namespace Tests\Feature;

use App\Participant;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AnonymizeParticipantFeatureTest extends TestCase
{
    use DatabaseTransactions;
    /** @var Participant */
    private $participant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->participant = Participant::find(5);
    }

    public function test_it_is_not_accessible_for_members()
    {
        $user = User::findOrFail(2); // Jon
        $this->actingAs($user)->get(action('ParticipantsController@anonymize'))
            ->assertStatus(403);

        $this->actingAs($user)->get(action('ParticipantsController@index'))
            ->assertDontSee('Anonimiseren');
    }

    public function test_it_is_not_accessible_for_participants()
    {
        $user = User::findOrFail(13); // jan
        $this->actingAs($user)->get(action('ParticipantsController@anonymize'))
            ->assertStatus(403);

        $this->actingAs($user)->get(action('ParticipantsController@index'))
            ->assertDontSee('Anonimiseren');
    }

    public function test_it_is_accessible_for_board()
    {
        $user = User::findOrFail(1); // Ranonkeltje
        $this->actingAs($user)->get(action('ParticipantsController@anonymize'))
            ->assertStatus(200);

        $this->actingAs($user)->get(action('ParticipantsController@index'))
            ->assertSee('Anonimiseren');
    }

    public function test_it_shows_people_to_anonomyze()
    {
        $user = User::findOrFail(1); // Ranonkeltje
        $this->actingAs($user)->get(action('ParticipantsController@anonymize'))
            ->assertStatus(200)
            ->assertSee('Jan Janssen')
            ->assertDontSee('Annabelle');
    }

    public function test_it_confirms_people_to_anonymize()
    {
        $user = User::findOrFail(1); // Ranonkeltje
        $this->actingAs($user)->get(action('ParticipantsController@anonymizeConfirm', [
            'participant' => [5]
        ]))
            ->assertStatus(200)
            ->assertSee('Jan Janssen')
            ->assertDontSee('Jaap')
            ;

        $this->actingAs($user)->get(action('ParticipantsController@anonymizeConfirm', [
            'participant' => [1]
        ]))
            ->assertStatus(200)
            ->assertSee('Jaap')
            ->assertDontSee('Jan');
    }

    public function test_it_anonymizes()
    {
        $user = User::findOrFail(1); // Ranonkeltje
        $this->actingAs($user)->post(action('ParticipantsController@anonymizeConfirm'), [
            'participant' => [5]
        ])
            ->assertStatus(302)
            ->assertRedirect(action('ParticipantsController@index'))
            ;

        // Dont see Jan after anonymization
        $this->actingAs($user)->get(action('ParticipantsController@anonymize'))
            ->assertDontSee('Jan');
        $this->actingAs($user)->get(action('ParticipantsController@index'))
            ->assertDontSee('jan@janssen.nl');
    }
}
