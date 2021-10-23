<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeclarationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('declarations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename')->nullable();
            $table->date('date');
            $table->integer('member_id')->unsigned();
            $table->string('description');
            $table->double('amount');
            $table->boolean('gift')->default(0);
            $table->date('closed_at')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('declarations');
    }
}
