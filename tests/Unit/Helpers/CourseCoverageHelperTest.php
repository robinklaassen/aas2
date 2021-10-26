<?php

namespace Tests\Unit\Helpers;

use App\Course;
use App\Event;
use App\Helpers\CourseCoverageHelper;
use App\Member;
use App\Participant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CourseCoverageHelperTest extends TestCase
{
    use DatabaseTransactions;

    private CourseCoverageHelper $courseCoverageHelper;
    private Event $camp;
    private Course $course;
    private Member $member;
    private Participant $participant;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->courseCoverageHelper = new CourseCoverageHelper();
        $this->camp = Event::findOrFail(8);  // Meikamp 2016, empty by default
        $this->course = Course::findOrFail(1);  // Nederlands
        $this->member = Member::findOrFail(10);  // Siep de Jong, no camps or courses
        $this->participant = Participant::findOrFail(4);  // Henk Janssen, no camps, klas 4

        $this->member->courses()->syncWithPivotValues($this->course->id, ['klas' => 6]);

        $this->camp->members()->sync($this->member->id);
        $this->camp->participants()->sync($this->participant->id);

        DB::table('course_event_participant')->insert([
            'course_id' => $this->course->id,
            'event_id' => $this->camp->id,
            'participant_id' => $this->participant->id
        ]);
    }

    public function testCoverageOk()
    {
        $this->assertEquals('ok', $this->courseCoverageHelper->getStatus($this->camp, $this->course));
    }

    public function testCoverageBadQuota()
    {
        $this->camp->members()->detach($this->member->id);
        $this->assertEquals('badquota', $this->courseCoverageHelper->getStatus($this->camp, $this->course));
    }

    public function testCoverageBadLevel()
    {
        $this->member->courses()->updateExistingPivot($this->course->id, ['klas' => 2]);
        $this->assertEquals('badlevel', $this->courseCoverageHelper->getStatus($this->camp, $this->course));

        // Also test unplaced option, if participant is unplaced there is no problem
        $this->assertEquals('ok', $this->courseCoverageHelper->getStatus($this->camp, $this->course, true));
    }
}
