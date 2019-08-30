<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicJSONApiTest extends TestCase
{
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
            ->assertJsonFragment(['naam' => 'Nieuwjaarskamp'])
            ->assertJsonFragment(['weekdag_start' => 'Maandag'])
            ->assertJsonFragment(['kamphuis_plaats' => 'Sneek']);
    }
}
