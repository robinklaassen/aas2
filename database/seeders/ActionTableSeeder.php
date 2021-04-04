<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Action;

class ActionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('actions')->delete();

        Action::create([
            'date' => '2018-10-10',
            'member_id' => 1,
            'description' => 'Supergaaf zijn enzo',
            'points' => 20
        ]);
    }
}
