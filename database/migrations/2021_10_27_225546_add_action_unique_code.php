<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class AddActionUniqueCode extends Migration
{
    public function up()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->string('code')
                ->nullable(true)
                ->default(null);

            $table->unique(['member_id', 'code']);
        });
    }

    public function down()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
