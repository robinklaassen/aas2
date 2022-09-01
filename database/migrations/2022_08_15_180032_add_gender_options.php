<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddGenderOptions extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE participants MODIFY COLUMN geslacht ENUM('M', 'V', 'X', 'N');");
        DB::statement("ALTER TABLE members MODIFY COLUMN geslacht ENUM('M', 'V', 'X', 'N');");
    }

    public function down()
    {
        DB::statement("ALTER TABLE participants MODIFY COLUMN geslacht ENUM('M', 'V');");
        DB::statement("ALTER TABLE members MODIFY COLUMN geslacht ENUM('M', 'V');");
    }
}
