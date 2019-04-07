<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Course;

class CourseTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('courses')->delete();
		
		Course::create([
			'naam' => 'Nederlands',
			'code' => 'Ne'
		]);
		
		Course::create([
			'naam' => 'Engels',
			'code' => 'En'
		]);
		
		Course::create([
			'naam' => 'Natuurkunde',
			'code' => 'Na'
		]);
		
		Course::create([
			'naam' => 'Wiskunde',
			'code' => 'Wi'
		]);
		
	}

}
