<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventParticipantTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('event_participant', function (Blueprint $table) {
            $table->integer('event_id')->unsigned();
            $table->integer('participant_id')->unsigned();
            $table->timestamps();
            $table->date('datum_betaling')->default('0000-00-00');
            $table->boolean('geplaatst')->default(0);

            $table->foreign('event_id')
                ->references('id')
                ->on('events')
                ->onDelete('cascade');

            $table->foreign('participant_id')
                ->references('id')
                ->on('participants')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('event_participant');
    }
}
