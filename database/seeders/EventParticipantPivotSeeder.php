<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Participant;
use DB;
use Illuminate\Database\Seeder;

class EventParticipantPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('event_participant')->delete();

        $participant = Participant::find(1);
        $participant->events()->attach(1);

        $participant = Participant::find(2);
        $participant->events()->attach(1, [
            'datum_betaling' => '2015-05-22',
            'geplaatst' => 1,
        ]);

        // This participant is added without courses.
        $participant = Participant::find(3);
        $participant->events()->attach(1, [
            'datum_betaling' => '2019-12-01',
            'geplaatst' => 1,
        ]);

        // online
        $participant = Participant::find(3);
        $participant->events()->attach(7, [
            'package_id' => 1,
            'geplaatst' => 1,
        ]);

        $participant = Participant::find(5);
        $participant->events()->attach(13, [
            'datum_betaling' => '2009-12-01',
            'geplaatst' => 1,
        ]);
    }
}
