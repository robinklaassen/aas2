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
        // remove participants show basic info for members
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

        removeCapability("member", "participants::info::show::basic");

        DB::table("capabilities")->insert(["name" => "participants::info::list", "description" => "Deelnemerinfo - Lijst"]);
        addCapability("board", "participants::info::list");
        addCapability("kantoorci", "participants::info::list");
        addCapability("kantoorci", "participants::info::list");
        removeCapability("kampci", "participants::account::create");
        removeCapability("kampci", "participants::account::delete");

        addCapability("kampci", "members::info::edit::password");
        addCapability("aasbaas", "members::info::edit::password");
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
