<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EventTest extends TestCase
{
    use DatabaseTransactions;

    public $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::findOrFail(1);
    }

    /**
     * Test the events index page
     */
    public function testIndex()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/events')
            ->assertStatus(200)
            ->assertSee('Nieuwjaarskamp', 'Training Meikamp', '/events/1');
    }

    /**
     * Test the show event page
     */
    public function testShow()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/events/1')
            ->assertStatus(200)
            ->assertSee('Bewerken', 'Venlo', 'Ranonkeltje', 'Vakdekking', 'Annabelle');
    }

    public function testExport()
    {
        // exports  event 1, it has participants with and without courses
        $response = $this
            ->actingAs($this->user)
            ->get('/events/1/export')
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/pdf');
    }

    public function testCreate()
    {
        $eventData = [
            'naam' => 'TestCamp',
            'code' => 'TST',
            'location_id' => '2',
            'openbaar' => '1',
            'datum_start' => '2022-12-12',
            'tijd_start' => '12:00',
            'datum_eind' => '2022-12-16',
            'tijd_eind' => '12:00',
            'type' => 'online',
            'package_type' => 'online-tutoring',
            'datum_voordag' => '',
            'prijs' => 0,
            'vol' => 0,
            'streeftal' => '5',
            'beschrijving' => 'Test!',
        ];

        $this
            ->actingAs($this->user)
            ->post('/events', $eventData)
            ->assertRedirect('/events');

        $databaseData = $eventData;
        $databaseData['datum_voordag'] = null;
        // database stores time differently
        $databaseData['tijd_start'] = '12:00:00';
        $databaseData['tijd_eind'] = '12:00:00';

        $this->assertDatabaseHas('events', $databaseData);
    }

    public function testExistingCampTrainingRelation()
    {
        $camp = Event::find(1);  // meikamp 2015
        $training = Event::find(3);  // training meikamp 2015

        $this->assertEquals($training, $camp->training());
        $this->assertEquals($camp, $training->camps()->first());
    }

    public function testMultipleCampsPerTrainingRelation()
    {
        $camp1 = Event::find(11); // zomerkamp 1 2016
        $camp2 = Event::find(12); // zomerkamp 2 2016
        $training = Event::find(15);  // training zomer 2016
        
        $this->assertEquals(collect([$camp1, $camp2]), $training->camps()->toBase());
    }

    public function testMissingCampTrainingRelation()
    {
        $camp = Event::find(13);  // yee-oldy-kamp
        $training = Event::find(14);  // training voor niks

        $this->assertNull($camp->training());
        $this->assertEmpty($training->camps());
    }
}
