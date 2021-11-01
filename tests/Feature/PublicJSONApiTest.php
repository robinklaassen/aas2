<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Event;
use Tests\TestCase;

class PublicJSONApiTest extends TestCase
{
    private $kampDataFragment = [
        'naam' => 'Nieuwjaarskamp',
        'weekdag_start' => 'Maandag',
        'kamphuis_plaats' => 'Sneek',
    ];

    private $trainingDataFragment = [
        'naam' => 'Training Nieuwjaarskamp',
        'weekdag_eind' => 'Zondag',
        'kamphuis_adres' => 'Vaalsestraat 23',
    ];

    /**
     * Test the camp info API
     */
    public function testCampInfo()
    {
        $response = $this
            ->get('/camp-info/1')
            ->assertExactJson([
                'id' => 1,
                'naam' => 'Meikamp',
                'prijs' => 400,
            ]);
    }

    /**
     * Test the calendar API for participants
     */
    public function testPartCal()
    {
        $response = $this
            ->get('/cal/part')
            ->assertJsonCount(Event::where('openbaar', '1')->whereIn('type', ['kamp', 'online'])->where('datum_eind', '>=', date('Y-m-d'))->count())
            ->assertJsonFragment($this->kampDataFragment);
    }

    /**
     * Test the calendar API for members
     */
    public function testFullCal()
    {
        $response = $this
            ->get('/cal/full')
            ->assertJsonCount(Event::where('openbaar', '1')->where('datum_eind', '>=', date('Y-m-d'))->count())
            ->assertJsonFragment($this->kampDataFragment)
            ->assertJsonFragment($this->trainingDataFragment);
    }
}
