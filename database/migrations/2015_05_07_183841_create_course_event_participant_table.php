<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCourseEventParticipantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_event_participant', function (Blueprint $table) {
            $table->integer('course_id')->unsigned();
            $table->integer('event_id')->unsigned();
            $table->integer('participant_id')->unsigned();
            $table->text('info')->nullable();
            
            $table->foreign('course_id')
                    ->references('id')
                    ->on('courses')
                    ->onDelete('cascade');
            
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
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('course_event_participant');
    }
}
