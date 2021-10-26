<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Participant;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AnonymizeParticipantFeatureTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var Participant
     */
    private $participant;

    protected function setUp(): void
    {
        parent::setUp();
        $this->participant = Participant::find(5);
    }

    public function testItIsNotAccessibleForMembers()
    {
        $user = User::findOrFail(4); // Jon
        $this->actingAs($user)->get(action('ParticipantsController@anonymize'))
            ->assertStatus(403);

        $this->actingAs($user)->get(action('ParticipantsController@index'))
            ->assertDontSee('Anonimiseren');
    }

    public function testItIsNotAccessibleForParticipants()
    {
        $user = User::findOrFail(13); // jan
        $this->actingAs($user)->get(action('ParticipantsController@anonymize'))
            ->assertStatus(403);

        $this->actingAs($user)->get(action('ParticipantsController@index'))
            ->assertDontSee('Anonimiseren');
    }

    public function testItIsAccessibleForBoard()
    {
        $user = User::findOrFail(1); // Ranonkeltje
        $this->actingAs($user)->get(action('ParticipantsController@anonymize'))
            ->assertStatus(200);

        $this->actingAs($user)->get(action('ParticipantsController@index'))
            ->assertSee('Anonimiseren');
    }

    public function testItIsAccessibleForKantoorci()
    {
        $user = User::findOrFail(2); // Jon
        $this->actingAs($user)->get(action('ParticipantsController@anonymize'))
            ->assertStatus(200);

        $this->actingAs($user)->get(action('ParticipantsController@index'))
            ->assertSee('Anonimiseren');
    }

    public function testItShowsPeopleToAnonomyze()
    {
        $user = User::findOrFail(1); // Ranonkeltje
        $this->actingAs($user)->get(action('ParticipantsController@anonymize'))
            ->assertStatus(200)
            ->assertSee('Jan Janssen')
            ->assertDontSee('Annabelle');
    }

    public function testItConfirmsPeopleToAnonymize()
    {
        $user = User::findOrFail(1); // Ranonkeltje
        $this->actingAs($user)->get(action('ParticipantsController@anonymizeConfirm', [
            'participant' => [5],
        ]))
            ->assertStatus(200)
            ->assertSee('Jan Janssen')
            ->assertDontSee('Jaap')
            ;

        $this->actingAs($user)->get(action('ParticipantsController@anonymizeConfirm', [
            'participant' => [1],
        ]))
            ->assertStatus(200)
            ->assertSee('Jaap')
            ->assertDontSee('Jan');
    }

    public function testItAnonymizes()
    {
        $user = User::findOrFail(1); // Ranonkeltje
        $this->actingAs($user)->post(action('ParticipantsController@anonymizeConfirm'), [
            'participant' => [5],
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
