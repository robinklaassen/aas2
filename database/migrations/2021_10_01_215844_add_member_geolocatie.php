<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMemberGeolocatie extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->point('geolocatie')->after('opmerkingen')->nullable()->default(null);
            $table->string('geolocatie_error')->after('geolocatie')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('geolocatie');
            $table->dropColumn('geolocatie_error');
        });
    }
}
