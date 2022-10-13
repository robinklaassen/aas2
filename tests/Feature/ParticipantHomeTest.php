<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ParticipantHomeTest extends TestCase
{
    use DatabaseTransactions;

    private $participant;

    private $user;

    private $event;

    protected function setUp(): void
    {
        parent::setUp();
        $this->participant = Participant::find(2);  // annabelle
        $this->user = $this->participant->user;
        $this->event = Event::find(5);  // nieuwjaarskamp 2090

        $this->user->privacy_accepted = true;
        $this->user->save();
    }

    public function testNoUpcomingCamps(): void
    {
        $this->actingAs($this->user)
            ->get('/home')
            ->assertSee('Schrijf je direct in!')
            ->assertDontSee(['Betaling', 'Inkomensverklaring', 'Geplaatst']);
    }

    public function testCampPaymentStatus(): void
    {
        $this->participant->events()->attach($this->event->id);

        $this->actingAs($this->user)
            ->get('/home')
            ->assertSee(['Betaling nog niet ontvangen!', '&euro; 300'], false)
            ->assertDontSee(['Betaling ontvangen op']);

        $this->participant->events()->updateExistingPivot($this->event->id, [
            'datum_betaling' => '2000-01-01',
        ]);

        $this->actingAs($this->user)
            ->get('/home')
            ->assertSee('Betaling ontvangen op 01-01-2000')
            ->assertDontSee(['Betaling nog niet ontvangen!']);
    }

    public function testIncomeStatementStatus(): void
    {
        $this->participant->events()->attach($this->event->id);

        $this->actingAs($this->user)
            ->get('/home')
            ->assertSee('Inkomensverklaring nog niet ontvangen!')
            ->assertDontSee(['Inkomensverklaring ontvangen', 'Geen inkomensverklaring nodig']);

        $this->participant->inkomensverklaring = '2015-01-01';
        $this->participant->save();
        $this->user->refresh();

        $this->actingAs($this->user)
            ->get('/home')
            ->assertSee('Inkomensverklaring ontvangen op 01-01-2015')
            ->assertDontSee(['Inkomensverklaring nog niet ontvangen!', 'Geen inkomensverklaring nodig']);

        $this->participant->inkomen = 0;
        $this->participant->save();
        $this->user->refresh();

        $this->actingAs($this->user)
            ->get('/home')
            ->assertSee('Geen inkomensverklaring nodig')
            ->assertDontSee(['Inkomensverklaring nog niet ontvangen!', 'Inkomensverklaring ontvangen op']);
    }

    public function testPlacedStatus(): void
    {
        $this->participant->events()->attach($this->event->id);

        $this->actingAs($this->user)
            ->get('/home')
            ->assertSee('Nog niet geplaatst')
            ->assertDontSee('Geplaatst voor het kamp');

        $this->participant->events()->updateExistingPivot($this->event->id, [
            'geplaatst' => true,
        ]);

        $this->actingAs($this->user)
            ->get('/home')
            ->assertSee('Geplaatst voor het kamp')
            ->assertDontSee('Nog niet geplaatst');
    }
}
