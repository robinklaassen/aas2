<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeclarationsOriginalFileName extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->string('original_filename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->dropColumn('original_filename');
        });
    }
}
