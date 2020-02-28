<?php

use App\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddRolesToExistingUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $part_role = Role::where("tag", "=", "participant")->firstOrFail();
        $memb_role = Role::where("tag", "=", "member")->firstOrFail();


        DB::statement('
            insert into user_role (user_id , role_id, created_by, created_at)
            select u.id, :role, 0, NOW()
              from users u
             where u.profile_type = :type
        ', [
            "type" => "App\\Participant",
            "role" => $part_role->id
        ]);

        DB::statement('
            insert into user_role (user_id , role_id, created_by, created_at)
            select u.id, :role, 0, NOW()
              from users u
             where u.profile_type = :type 
        ', [
            "type" => "App\\Member",
            "role" => $memb_role->id
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("truncate table user_role");
    }
}
