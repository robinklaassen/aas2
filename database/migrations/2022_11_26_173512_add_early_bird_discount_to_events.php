<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class AddEarlyBirdDiscountToEvents extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('vroegboek_korting_percentage')->nullable();
            $table->date('vroegboek_korting_datum_eind')->nullable();
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumns(['vroegboek_korting_percentage', 'vroegboek_korting_datum_eind']);
        });
    }
}
