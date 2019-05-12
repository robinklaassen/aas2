<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Capability;

class CapabilitiesSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('capabilities')->delete();

        Capability::create([
            "name" => "export-event-participants"
        ]);
        Capability::create([
            "name" => "create-event"
        ]);
        Capability::create([
            "name" => "edit-event"
        ]);
        Capability::create([
            "name" => "show-member-secret-notes"
        ]);
        Capability::create([
            "name" => "show-member-kmg"
        ]);
        Capability::create([
            "name" => "edit-participant"
        ]);
    }
}
