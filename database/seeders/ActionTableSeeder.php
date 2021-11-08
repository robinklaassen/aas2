<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Action;
use DB;
use Illuminate\Database\Seeder;

class ActionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('actions')->delete();

        Action::create([
            'date' => '2018-10-10',
            'member_id' => 1,
            'description' => 'Supergaaf zijn enzo',
            'points' => 20,
        ]);
    }
}
