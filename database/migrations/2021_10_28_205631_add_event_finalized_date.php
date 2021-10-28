<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEventFinalizedDate extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dateTime('finalized_at')
                ->nullable(true);
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('finalized_at');
        });
    }
}
