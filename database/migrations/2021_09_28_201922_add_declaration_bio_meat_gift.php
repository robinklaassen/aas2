<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDeclarationBioMeatGift extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->enum('declaration_type', ['pay', 'gift', 'pay-biomeat'])->default('pay');
        });
        DB::update("UPDATE declarations set declaration_type = CASE WHEN gift = true THEN 'gift' ELSE 'pay' END");

        Schema::table('declarations', function (Blueprint $table) {
            $table->dropColumn('gift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->boolean('gift')->default(false);
        });

        DB::update("UPDATE declarations set gift = CASE WHEN declaration_type = 'pay' THEN false ELSE true END");

        Schema::table('declarations', function (Blueprint $table) {
            $table->dropColumn('declaration_type');
        });
    }
}
