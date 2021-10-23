<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ConfidentialCounselorRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("roles")->insert([
            "title" => "Vertrouwenspersoon",
            "tag" => "counselor"
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table("roles")->where("tag", "counselor")->delete();
    }
}
