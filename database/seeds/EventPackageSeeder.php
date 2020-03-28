<?php

use Illuminate\Database\Seeder;
use App\EventPackage;

class EventPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('event_packages')->delete();

        EventPackage::create([
            'title' => '2 uur',
            'description' => '2 uur digitaal bijles',
            'price' => 15
        ]);

        EventPackage::create([
            'title' => '5 uur',
            'description' => '5 uur digitaal bijles',
            'price' => 35
        ]);

        EventPackage::create([
            'title' => '10 uur',
            'description' => '10 uur digitaal bijles',
            'price' => 65
        ]);
    }
}
