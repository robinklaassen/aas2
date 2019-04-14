<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->double('bs-uren');
            $table->integer('bs-mening');
            $table->integer('bs-tevreden');
            $table->integer('bs-manier');
            $table->string('bs-manier-mening')->nullable();
            $table->integer('bs-thema');
            $table->string('bs-thema-wat')->nullable();
            $table->integer('kh-slaap');
            $table->string('kh-slaap-wrm')->nullable();
            $table->integer('kh-bijspijker');
            $table->string('kh-bijspijker-wrm')->nullable();
            $table->integer('kh-geheel');
            $table->string('kh-geheel-wrm')->nullable();
            $table->string('leidingploeg');
            $table->integer('slaaptijd');
            $table->string('slaaptijd-hoe')->nullable();
            $table->integer('kamplengte');
            $table->string('kamplengte-wrm')->nullable();
            $table->string('eten');
            $table->string('avond-leukst');
            $table->string('avond-minst');
            $table->string('allerleukst');
            $table->string('allervervelendst');
            $table->double('cijfer');
            $table->string('nogeens');
            $table->string('kampkeuze')->nullable();
            $table->string('tip')->nullable();
            $table->string('verder')->nullable();
            $table->timestamps();

            $table->foreign('event_id')
					->references('id')
					->on('events')
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
        Schema::dropIfExists('reviews');
    }
}
