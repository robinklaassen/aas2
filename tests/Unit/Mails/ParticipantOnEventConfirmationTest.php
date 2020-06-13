<?php

namespace Tests\Unit;

use App\Event;
use App\Mail\participants\OnEventConfirmation;
use App\Participant;
use Tests\TestCase;

class ParticipantOnEventConfirmationTest extends TestCase
{

    protected $participant;
    protected $event;
    protected $fakeCourses = [
        ["naam" => "engels", "info"=> "dingen"]
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->participant = Participant::find(2); // annabelle
        $this->event = Event::find(5); // Nieuwjaarskamp
    }

    public function testParticipant() 
    {

        $mail = new OnEventConfirmation(
            $this->participant,
            $this->event,
            $this->fakeCourses,
            100.00,
            0,
            "existing"
        );

        $content = $mail->render();

        $this->assertContains(
            $this->event->naam,
            $content,
            "Should contain event name"
        );

        $this->assertContains(
            $this->event->location->plaats,
            $content,
            "Should contain event location"
        );

        $this->assertContains(
            $this->event->datum_start->format('d-m-Y'),
            $content,
            "Should contain event start date"
        );

        $this->assertContains(
            $this->event->datum_eind->format('d-m-Y'),
            $content,
            "Should contain event end date"
        );

        foreach($this->fakeCourses as $course) {
            $this->assertContains(
                $course["naam"],
                $content,
                "Should course name"
            );
            $this->assertContains(
                $course["info"],
                $content,
                "Should course info"
            );
        }

        

    }

    public function testExistingParticipantWithoutIdeal() 
    {
        $mail = new OnEventConfirmation(
            $this->participant,
            $this->event,
            $this->fakeCourses,
            100.00,
            0,
            "existing"
        );

        $content = $mail->render();

        $this->assertContains(
            env("BANKING_INFORMATION"),
            $content,
            "Should contain banking information"
        );
    }

    public function testNewParticipantWithDiscount() 
    {
        $mail = new OnEventConfirmation(
            $this->participant,
            $this->event,
            $this->fakeCourses,
            100.00,
            0,
            "new"
        );

        $content = $mail->render();

        $this->assertContains(
            "U heeft een korting op de kampprijs aangevraagd",
            $content,
            "Should contain discount text"
        );
        $this->assertContains(
            nl2br(env("ADDRESS_INFORMATION")),
            $content,
            "Should contain address information"
        );
    }
}