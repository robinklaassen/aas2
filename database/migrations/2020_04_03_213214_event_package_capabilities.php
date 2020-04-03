<?php

use App\Capability;
use App\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EventPackageCapabilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("capabilities")->insert([
            [
                "name" => "event-packages::show",
                "description" => "Event Paketten - Inzien"
            ],
            [
                "name" => "event-packages::edit",
                "description" => "Event Paketten - Wijzigen"
            ],
            [
                "name" => "event-packages::create",
                "description" => "Event Paketten - Aanmaken"
            ],
            [
                "name" => "event-packages::delete",
                "description" => "Event Paketten - Delete"
            ]
        ]);


        function addCapability2($role, $capa)
        {
            $capa = Capability::where("name", "=", $capa)->firstOrFail();
            $role = Role::where("tag", "=", $role)->firstOrFail();
            $role->capabilities()->attach($capa->id);
        }

        addCapability2("board", "event-packages::show");
        addCapability2("board", "event-packages::edit");
        addCapability2("board", "event-packages::create");
        addCapability2("board", "event-packages::delete");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table("capabilities")->where('name', 'like', 'event-packages::%')->delete();
    }
}
