<?php

declare(strict_types=1);

use App\Models\Capability;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class EventPackageCapabilities extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::table('capabilities')->insert([
            [
                'name' => 'event-packages::show',
                'description' => 'Event Pakketten - Inzien',
            ],
            [
                'name' => 'event-packages::edit',
                'description' => 'Event Pakketten - Wijzigen',
            ],
            [
                'name' => 'event-packages::create',
                'description' => 'Event Pakketten - Aanmaken',
            ],
            [
                'name' => 'event-packages::delete',
                'description' => 'Event Pakketten - Verwijderen',
            ],
        ]);

        function addCapability2($role, $capa)
        {
            $capa = Capability::where('name', '=', $capa)->firstOrFail();
            $role = Role::where('tag', '=', $role)->firstOrFail();
            $role->capabilities()->attach($capa->id);
        }

        addCapability2('board', 'event-packages::show');
        addCapability2('board', 'event-packages::edit');
        addCapability2('board', 'event-packages::create');
        addCapability2('board', 'event-packages::delete');
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::table('capabilities')->where('name', 'like', 'event-packages::%')->delete();
    }
}
