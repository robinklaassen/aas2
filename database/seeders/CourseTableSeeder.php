<?php

namespace Database\Seeders;

use App\Course;
use DB;
use Illuminate\Database\Seeder;

class CourseTableSeeder extends Seeder
{
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
