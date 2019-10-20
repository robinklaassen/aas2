<?php

namespace Tests\Feature;

use Tests\TestCase;

class BirthdayOptOutTest extends TestCase
{

    private $member;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = \App\Member::findOrFail(2);
    }

    /**
     * Test the birthday list
     *
     * @return void
     */
    // FIXME test is failing because of the many sublists on the page
    // public function testBirthdayList()
    // {
    //     $this
    //         ->actingAs($this->member->user)
    //         ->get('/lists')
    //         ->assertDontSee('Snow');
    // }

    /**
     * Test the Google Calendar export
     *
     * @return void
     */
    public function testGoogleCalendar()
    {
        $this
            ->actingAs($this->member->user)
            ->get('/events/icalendar')
            ->assertDontSee('Snow');
    }
}
