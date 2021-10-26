<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberSkillTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('member_skill', function (Blueprint $table) {
            $table->integer('member_id')->unsigned();
            $table->integer('skill_id')->unsigned();
            $table->timestamps();

            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->onDelete('cascade');

            $table->foreign('skill_id')
                ->references('id')
                ->on('skills')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('member_skill');
    }
}
