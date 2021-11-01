<?php

declare(strict_types=1);

use App\Models\Capability;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;

class AddPasswordRightToKantoorci extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        function addCapability_20200728($role, $capa)
        {
            $capa = Capability::where('name', '=', $capa)->firstOrFail();
            $role = Role::where('tag', '=', $role)->firstOrFail();
            $role->capabilities()->attach($capa->id);
        }

        addCapability_20200728('kantoorci', 'participants::info::edit::password');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
