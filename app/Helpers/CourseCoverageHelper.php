<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Course;
use App\Models\Event;

class CourseCoverageHelper
{
    // Return types for the getStatus method
    public const STATUS_BAD_QUOTA = 'badquota';

    public const STATUS_BAD_LEVEL = 'badlevel';

    public const STATUS_OK = 'ok';

    // During tutoring on camps there is a default maximum number of participants per member
    private const NUM_PARTICIPANTS_PER_MEMBER = 3;

    // Get coverage status for a specific course on a camp
    public function getStatus(Event $camp, Course $course, bool $onlyPlacedParticipants = false): string
    {
        $members = $course->members()->onEvent($camp)->get();
        $participants = $course->participantsOnCamp($camp, $onlyPlacedParticipants);

        $memberLevels = $members->pluck('pivot.klas')->flatMap(
            fn ($v) => array_fill(0, self::NUM_PARTICIPANTS_PER_MEMBER, $v)
        )->sortDesc();
        $participantLevels = $participants->pluck('klas')->sortDesc();

        // Check if number of members is sufficient
        if (3 * $members->count() < $participants->count()) {
            return self::STATUS_BAD_QUOTA;
        }

        // Check if levels are sufficient
        foreach ($memberLevels->zip($participantLevels) as [$ml, $pl]) {
            if ($ml < $pl) {
                return self::STATUS_BAD_LEVEL;
            }
        }

        // Otherwise all is fine!
        return self::STATUS_OK;
    }
}
