<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('tag');
            $table->unique('tag');
        });


        $roles = [
            ["Aasbaas", "aasbaas"],
            ["Bestuurslid", "board"],
            ["Voorzitter", "president"],
            ["Penningmeester", "treasurer"],
            ["Kampcommissie", "kampci"],
            ["Kantoorcommissie", "kantoorci"],
            ["Promocommissie", "promoci"],
            ["Trainingscommissie", "trainerci"],
            ["Trainer", "trainer"],
            ["Normaal lid", "member"],
            ["Deelnemer", "participant"]
        ];
        $all = array_map(function ($i) {
            return ["title" => $i[0], "tag" => $i[1]];
        }, $roles);

        DB::table("roles")->insert($all);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
