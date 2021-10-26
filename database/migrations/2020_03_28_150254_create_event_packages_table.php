<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEventPackagesTable extends Migration
{
    /**
     * Run the migrations.
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
            $table->integer('package_id')->unsigned()->nullable();

            $table->foreign('package_id')
                ->references('id')
                ->on('event_packages')
                ->onDelete('cascade');
        });

        DB::statement("ALTER TABLE events MODIFY COLUMN type enum('kamp','training','overig', 'online') not null");
        Schema::table('events', function (Blueprint $table) {
            $table->text('package_type', ['online-tutoring', 'other'])->nullable();
        });

        DB::table('event_packages')->insert([
            [
                'code' => '2UUR',
                'title' => '2 uur',
                'description' => '2 uur digitaal bijles',
                'price' => 15,
            ], [
                'code' => '5UUR',
                'title' => '5 uur',
                'description' => '5 uur digitaal bijles',
                'price' => 35,
            ], [
                'code' => '10UUR',
                'title' => '10 uur',
                'description' => '10 uur digitaal bijles',
                'price' => 65,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('event_packages');

        Schema::table('event_participant', function (Blueprint $table) {
            $table->dropForeign('package_id');
            $table->dropColumn('package_id');
        });
    }
}
