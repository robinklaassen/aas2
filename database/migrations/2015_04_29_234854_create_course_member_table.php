<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCourseMemberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_member', function (Blueprint $table) {
            $table->integer('course_id')->unsigned();
            $table->integer('member_id')->unsigned();
            $table->integer('klas');
            
            $table->foreign('course_id')
                    ->references('id')
                    ->on('courses')
                    ->onDelete('cascade');
                    
            $table->foreign('member_id')
                    ->references('id')
                    ->on('members')
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
        Schema::drop('course_member');
    }
}
