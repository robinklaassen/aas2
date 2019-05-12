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
			CapabilitiesSeeder::class,
			RoleTableSeeder::class,
			UserTableSeeder::class,
			ActionTableSeeder::class,
			ReviewTableSeeder::class,
			MemberReviewPivotSeeder::class
		]);
	}
}
