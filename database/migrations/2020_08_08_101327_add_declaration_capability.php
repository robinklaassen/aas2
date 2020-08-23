<?php

use App\Helpers\CapabilityHelper;
use Illuminate\Database\Migrations\Migration;


class AddDeclarationCapability extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CapabilityHelper::new(
            'declarations::self',
            'Declaraties - Eigen beheren',
            'member'
        );
        
        CapabilityHelper::new(
            'declarations::create',
            'Declaraties - Aanmaken',
            'member'
        );

        CapabilityHelper::new(
            'declarations::show',
            'Declaraties - Inzien',
            'treasurer'
        );
        CapabilityHelper::new(
            'declarations::delete',
            'Declaraties - Verwijderen',
            'treasurer'
        );
        CapabilityHelper::new(
            'declarations::edit',
            'Declaraties - Bewerken',
            'treasurer'
        );

        CapabilityHelper::new(
            'declarations::process',
            'Declaraties - Verwerken',
            'treasurer'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CapabilityHelper::delete([
            'declarations::self',
            'declarations::create',
            'declarations::show',
            'declarations::delete',
            'declarations::edit',
            'declarations::process'
        ]);
    }
}
