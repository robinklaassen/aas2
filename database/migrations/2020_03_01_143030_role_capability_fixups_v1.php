<?php

use App\Capability;
use App\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RoleCapabilityFixupsV1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        function removeCapability($role, $capa)
        {
            $capa = Capability::where("name", "=", $capa)->firstOrFail();
            $role = Role::where("tag", "=", $role)->firstOrFail();
            $role->capabilities()->detach($capa->id);
        }

        function addCapability($role, $capa)
        {
            $capa = Capability::where("name", "=", $capa)->firstOrFail();
            $role = Role::where("tag", "=", $role)->firstOrFail();
            $role->capabilities()->attach($capa->id);
        }


        // Remove show basic participant info from members
        removeCapability("member", "participants::info::show::basic");


        // add seperate participants list capability
        DB::table("capabilities")->insert(["name" => "participants::info::list", "description" => "Deelnemerinfo - Lijst"]);

        addCapability("board", "participants::info::list");
        addCapability("kantoorci", "participants::info::list");
        addCapability("kantoorci", "participants::info::list");
        removeCapability("kampci", "participants::account::create");
        removeCapability("kampci", "participants::account::delete");


        // add change password rights
        addCapability("kampci", "members::info::edit::password");
        addCapability("aasbaas", "members::info::edit::password");


        // add member private info for board
        addCapability("board", "members::info::show::private");


        // Capabilities for actions
        DB::table("capabilities")->insert([
            ["name" => "actions::show", "description" => "Punten Acties - Inzien"],
            ["name" => "actions::create", "description" => "Punten Acties - Aanmaken"],
            ["name" => "actions::edit", "description" => "Punten Acties - Aanpassen"],
            ["name" => "actions::delete", "description" => "Punten Acties - Verwijderen"],
        ]);
        addCapability("board", "actions::show");
        addCapability("board", "actions::create");
        addCapability("board", "actions::edit");
        addCapability("board", "actions::delete");

        addCapability("promoci", "actions::show");
        addCapability("promoci", "actions::create");
        addCapability("promoci", "actions::edit");
        addCapability("promoci", "actions::delete");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
