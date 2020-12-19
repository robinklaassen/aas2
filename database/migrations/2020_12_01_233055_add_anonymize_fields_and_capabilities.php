<?php

use App\Helpers\CapabilityHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddAnonymizeFieldsAndCapabilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        CapabilityHelper::new(
            'participants::anonymize',
            'Deelnemers - Anonimiseren',
            'board'
        );
        CapabilityHelper::attach('participants::anonymize', 'kantoorci');

        Schema::table('participants', function (Blueprint $table) {
            $table->timestamp('anonymized_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        CapabilityHelper::delete([
            'participants::anonymize'
        ]);

        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('anonymized_at');
        });
    }
}
