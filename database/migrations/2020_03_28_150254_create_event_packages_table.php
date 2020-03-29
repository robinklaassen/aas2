<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEventPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['online-tutoring', 'other'])->default('online-tutoring');
            $table->text('code');
            $table->text('title');
            $table->text('description')->nullable();
            $table->integer('price')->nullable();
            $table->timestamps();
        });


        Schema::table('event_participant', function (Blueprint $table) {
            $table->integer("package_id")->unsigned()->nullable();

            $table->foreign('package_id')
                ->references('id')
                ->on('event_packages')
                ->onDelete('cascade');
        });


        DB::statement("ALTER TABLE events MODIFY COLUMN type enum('kamp','training','overig', 'online') not null");
        Schema::table('events', function (Blueprint $table) {
            $table->text('package_type', ['online-tutoring', 'other'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_packages');

        Schema::table('event_participant', function (Blueprint $table) {
            $table->dropForeign("package_id");
            $table->dropColumn("package_id");
        });
    }
}
