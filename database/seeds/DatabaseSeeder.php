<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

<<<<<<< HEAD
		$this->call('MemberTableSeeder');
		$this->call('LocationTableSeeder');
		$this->call('EventTableSeeder');
		$this->call('CourseTableSeeder');
		$this->call('EventMemberPivotSeeder');
		$this->call('CourseMemberPivotSeeder');
		$this->call('ParticipantTableSeeder');
		$this->call('EventParticipantPivotSeeder');
		$this->call('CourseEventParticipantPivotSeeder');
		$this->call('CapabilitiesSeeder');
		$this->call('RoleTableSeeder');
		$this->call('UserTableSeeder');
=======
		$this->call([
			MemberTableSeeder::class,
			LocationTableSeeder::class,
			EventTableSeeder::class,
			CourseTableSeeder::class,
			EventMemberPivotSeeder::class,
			CourseMemberPivotSeeder::class,
			ParticipantTableSeeder::class,
			EventParticipantPivotSeeder::class,
			CourseEventParticipantPivotSeeder::class,
			UserTableSeeder::class,
			ActionTableSeeder::class,
			ReviewTableSeeder::class,
			MemberReviewPivotSeeder::class
		]);
>>>>>>> change/upgrade
	}
}
