<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicJSONApiTest extends TestCase
{

    private $kampDataFragment = [
        'naam' => 'Nieuwjaarskamp',
        'weekdag_start' => 'Maandag',
        'kamphuis_plaats' => 'Sneek'
    ];
    private $trainingDataFragment = [
        'naam' => 'Training Nieuwjaarskamp',
        'weekdag_eind' => 'Zondag',
        'kamphuis_adres' => 'Vaalsestraat 23'
    ];

    /**
     * Test the camp info API
     *
     * @return void
     */
    public function testCampInfo()
    {
        $response = $this
            ->get('/camp-info/1')
            ->assertExactJson(['id' => 1, 'naam' => 'Meikamp', 'prijs' => 400]);
    }

    /**
     * Test the calendar API for participants
     * 
     * @return void
     */
    public function testPartCal()
    {
        $response = $this
            ->get('/cal/part')
            ->assertJsonCount(1)
            ->assertJsonFragment($this->kampDataFragment);
    }

    /**
     * Test the calendar API for members
     * 
     * @return void
     */
    public function testFullCal()
    {
        $response = $this
            ->get('/cal/full')
            ->assertJsonCount(2)
            ->assertJsonFragment($this->kampDataFragment)
            ->assertJsonFragment($this->trainingDataFragment);
    }
}
