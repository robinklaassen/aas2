<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBirthdayOptOut extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->boolean('publish_birthday')
                ->default(1)
                ->after('rijbewijs');  // TODO after clause not working properly
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('publish_birthday');
        });
    }
}
