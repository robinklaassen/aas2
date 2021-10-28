<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('naam');
            $table->string('code');
            $table->enum('type', ['kamp', 'training', 'overig']);
            $table->date('datum_voordag')->nullable();
            $table->date('datum_start');
            $table->time('tijd_start');
            $table->date('datum_eind');
            $table->time('tijd_eind');
            $table->integer('location_id')->unsigned()->nullable();
            $table->integer('prijs')->nullable();
            $table->integer('streeftal')->nullable();
            $table->boolean('vol')->default(0);
            $table->boolean('openbaar')->default(1);
            $table->text('beschrijving')->nullable();
            $table->text('opmerkingen')->nullable();
            $table->timestamps();

            $table->foreign('location_id')
                ->references('id')
                ->on('locations')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('events');
    }
}
