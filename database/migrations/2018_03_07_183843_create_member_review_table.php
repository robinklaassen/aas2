<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberReviewTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('member_review', function (Blueprint $table) {
            $table->integer('member_id')->unsigned();
            $table->integer('review_id')->unsigned();
            $table->integer('stof');
            $table->integer('aandacht');
            $table->integer('mening');
            $table->integer('tevreden');
            $table->string('bericht')->nullable();

            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->onDelete('cascade');

            $table->foreign('review_id')
                ->references('id')
                ->on('reviews')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('member_review');
    }
}
