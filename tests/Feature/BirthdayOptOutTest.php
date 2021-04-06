<?php

namespace Tests\Feature;

use Tests\TestCase;
use Symfony\Component\DomCrawler\Crawler;
use Sunra\PhpSimple\HtmlDomParser;

class BirthdayOptOutTest extends TestCase
{

    private $member;

    protected function setUp(): void
    {
        parent::setUp();
        $this->member = \App\Member::findOrFail(2);  // Jön Snow
    }

    /**
     * Test the birthday list
     *
     * @return void
     */
    public function testBirthdayList()
    {
        $this
            ->actingAs($this->member->user)
            ->get('/lists')
            ->assertSeeInOrder(['id="verjaardag"', 'Ranonkeltje', 'id="more"'], false);
    }

    /**
     * Test the Google Calendar export
     *
     * @return void
     */
    public function testGoogleCalendarAsMember()
    {
        $this
            ->actingAs($this->member->user)
            ->get('/events/icalendar')
            ->assertSee('Verjaardag Ranonkeltje van Anderwijs')
            ->assertDontSee('Snow');
    }

    /**
     * Test the Google Calendar export
     *
     * @return void
     */
    public function testGoogleCalendarPublic()
    {
        $this
            ->get('/events/icalendar')
            ->assertDontSee('Verjaardag Ranonkeltje van Anderwijs')
            ->assertDontSee('Snow');
    }
}
