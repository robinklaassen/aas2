<?php

declare(strict_types=1);

use App\Helpers\CapabilityHelper;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnonymizeFieldsAndCapabilities extends Migration
{
    /**
     * Run the migrations.
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
     */
    public function down()
    {
        CapabilityHelper::delete([
            'participants::anonymize',
        ]);

        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn('anonymized_at');
        });
    }
}
