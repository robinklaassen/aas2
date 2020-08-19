<?php

use Illuminate\Database\Seeder;
use App\Member;

class MemberSkillPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('member_skill')->delete();

        $member = Member::find(1);
        $member->skills()->attach([1, 2]);

        $member = Member::find(2);
        $member->skills()->attach([3]);
    }
}
