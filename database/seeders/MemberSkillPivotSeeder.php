<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Member;
use DB;
use Illuminate\Database\Seeder;

class MemberSkillPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
