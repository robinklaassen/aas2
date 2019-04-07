<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Member;

class EventMemberPivotSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('event_member')->delete();
		
		$member = Member::find(1);
		$member->events()->attach([1,2,4]);
		
		$member = Member::find(2);
		$member->events()->attach([1,3]);
		
	}

}
