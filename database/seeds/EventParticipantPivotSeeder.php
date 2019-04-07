<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Participant;

class EventParticipantPivotSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('event_participant')->delete();
		
		$participant = Participant::find(1);
		$participant->events()->attach(1);
		
		$participant = Participant::find(2);
		$participant->events()->attach(1);
		$participant->events()->updateExistingPivot(1, ['datum_betaling' => '2015-05-22']);
		
	}

}
