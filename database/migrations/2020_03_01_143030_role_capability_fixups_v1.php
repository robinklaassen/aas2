<?php

declare(strict_types=1);

use App\Capability;
use App\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RoleCapabilityFixupsV1 extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        function removeCapability($role, $capa)
        {
            $capa = Capability::where('name', '=', $capa)->firstOrFail();
            $role = Role::where('tag', '=', $role)->firstOrFail();
            $role->capabilities()->detach($capa->id);
        }

        function addCapability($role, $capa)
        {
            $capa = Capability::where('name', '=', $capa)->firstOrFail();
            $role = Role::where('tag', '=', $role)->firstOrFail();
            $role->capabilities()->attach($capa->id);
        }

        // Remove show basic participant info from members
        removeCapability('member', 'participants::info::show::basic');

        // add seperate participants list capability
        DB::table('capabilities')->insert([
            'name' => 'participants::info::list',
            'description' => 'Deelnemerinfo - Lijst',
        ]);

        addCapability('board', 'participants::info::list');
        addCapability('kantoorci', 'participants::info::list');
        addCapability('kantoorci', 'participants::info::list');

        // remove participant account creation for participants
        removeCapability('kampci', 'participants::account::create');
        removeCapability('kampci', 'participants::account::delete');

        // add change password rights
        addCapability('kampci', 'members::info::edit::password');
        addCapability('aasbaas', 'members::info::edit::password');

        // add member private info for board
        addCapability('board', 'members::info::show::private');

        // Capabilities for actions
        DB::table('capabilities')->insert([
            [
                'name' => 'actions::show',
                'description' => 'Punten Acties - Inzien',
            ],
            [
                'name' => 'actions::create',
                'description' => 'Punten Acties - Aanmaken',
            ],
            [
                'name' => 'actions::edit',
                'description' => 'Punten Acties - Aanpassen',
            ],
            [
                'name' => 'actions::delete',
                'description' => 'Punten Acties - Verwijderen',
            ],
        ]);
        addCapability('board', 'actions::show');
        addCapability('board', 'actions::create');
        addCapability('board', 'actions::edit');
        addCapability('board', 'actions::delete');

        DB::table('roles')->insert([
            'title' => 'ranonkeltje',
            'tag' => 'ranonkeltje',
        ]);

        // Members see basic info of other members
        addCapability('member', 'members::info::show::basic');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
    }
}
