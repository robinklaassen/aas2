<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ConfidentialCounselorRole extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::table('roles')->insert([
            'title' => 'Vertrouwenspersoon',
            'tag' => 'counselor',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::table('roles')->where('tag', 'counselor')->delete();
    }
}
