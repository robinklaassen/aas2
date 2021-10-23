<?php

namespace App\Helpers;

use App\Course;
use App\Event;
use Illuminate\Support\Facades\DB;

class CourseCoverageHelper
{
    // Get coverage status for a specific course on a camp
    public static function getStatus(Event $camp, Course $course, bool $onlyPlacedParticipants = false): string
    {
        $members = $course->members()->onEvent($camp)->get();
        $participants = DB::table('course_event_participant')
            ->where('event_id', $camp->id)
            ->where('course_id', $course->id)
            ->join('participants', 'course_event_participant.participant_id', '=', 'participants.id')
            ->get();
        
        if ($onlyPlacedParticipants) {
            $participants = $participants->filter(fn ($p) => $camp->participants->contains($p->id));
        }

        // Check if number of members is sufficient
        if (3 * $members->count() < $participants->count())
            return 'badquota';
        
        // Check if levels are sufficient
        $memberLevels = $members->pluck('pivot.klas')->flatMap(fn ($v) => array_fill(0, 3, $v))->sortDesc();
        $participantLevels = $participants->pluck('klas')->sortDesc();

        foreach ($memberLevels->zip($participantLevels) as [$ml, $pl]) {
            if ($ml < $pl)
                return 'badlevel';
        }

        // Otherwise all is fine!
        return 'ok';
    }
}
