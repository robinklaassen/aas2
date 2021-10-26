<?php

declare(strict_types=1);

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class CourseEventParticipantPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('course_event_participant')->delete();

        DB::table('course_event_participant')->insert([
            [
                'course_id' => 1,
                'event_id' => 1,
                'participant_id' => 1,
                'info' => 'Moeilijkheden zijn vooral grammatica en spelling',
            ],
            [
                'course_id' => 2,
                'event_id' => 1,
                'participant_id' => 1,
                'info' => 'Ai kent spiek inglies ferrie wel',
            ],
            [
                'course_id' => 4,
                'event_id' => 1,
                'participant_id' => 1,
                'info' => 'Hier staat echt een enorm verhaal, je weet wel, als een ouder echt een epistel meestuurt over de leerproblemen van zijn/haar zoon of dochter.',
            ],
            [
                'course_id' => 3,
                'event_id' => 1,
                'participant_id' => 2,
                'info' => 'Bij het volgende vak staat niets',
            ],
            [
                'course_id' => 4,
                'event_id' => 1,
                'participant_id' => 2,
                'info' => null,
            ],
            [
                'course_id' => 3,
                'event_id' => 7,
                'participant_id' => 3,
                'info' => 'Wauw, online bijles, wat vet',
            ],
        ]);
    }
}
