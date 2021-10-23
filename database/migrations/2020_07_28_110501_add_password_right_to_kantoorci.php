<?php

use App\Capability;
use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddPasswordRightToKantoorci extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        function addCapability_20200728($role, $capa)
        {
            $capa = Capability::where("name", "=", $capa)->firstOrFail();
            $role = Role::where("tag", "=", $role)->firstOrFail();
            $role->capabilities()->attach($capa->id);
        }
        
        addCapability_20200728("kantoorci", "participants::info::edit::password");
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
