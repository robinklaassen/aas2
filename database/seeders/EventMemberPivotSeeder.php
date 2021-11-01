<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Member;
use DB;
use Illuminate\Database\Seeder;

class EventMemberPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('event_member')->delete();

        $member = Member::find(1);
        $member->events()->attach([1, 2, 4]);

        $member = Member::find(2);
        $member->events()->attach([1, 3]);
    }
}
