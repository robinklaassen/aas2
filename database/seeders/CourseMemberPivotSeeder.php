<?php

namespace Database\Seeders;

use App\Member;
use DB;
use Illuminate\Database\Seeder;

class CourseMemberPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('course_member')->delete();
        
        $member = Member::find(1);
        $member->courses()->attach([1, 2, 3, 4]);
        $member->courses()->updateExistingPivot(1, ['klas' => '6']);
        $member->courses()->updateExistingPivot(2, ['klas' => '6']);
        $member->courses()->updateExistingPivot(3, ['klas' => '6']);
        $member->courses()->updateExistingPivot(4, ['klas' => '6']);
        
        $member = Member::find(2);
        $member->courses()->attach([2]);
        $member->courses()->updateExistingPivot(2, ['klas' => '2']);
        
        $member = Member::find(3);
        $member->courses()->attach([3, 4]);
        $member->courses()->updateExistingPivot(3, ['klas' => '4']);
        $member->courses()->updateExistingPivot(4, ['klas' => '4']);
    }
}
