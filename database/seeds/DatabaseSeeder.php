<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('MemberTableSeeder');
		$this->call('LocationTableSeeder');
		$this->call('EventTableSeeder');
		$this->call('CourseTableSeeder');
		$this->call('EventMemberPivotSeeder');
		$this->call('CourseMemberPivotSeeder');
		$this->call('ParticipantTableSeeder');
		$this->call('EventParticipantPivotSeeder');
		$this->call('CourseEventParticipantPivotSeeder');
		$this->call('UserTableSeeder');
	}

}
