<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Comments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {

            $table->increments('id');
            $table->string('text');
            $table->boolean("is_secret");
            $table->string('entity_type');
            $table->integer('entity_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        DB::statement("INSERT INTO users (id, username, password, profile_id, profile_type, is_admin) VALUES ('0', 'SYS', '', 0, 'App\\\\Member', true)");

        DB::statement("
            INSERT INTO comments (text, entity_type, entity_id, is_secret, user_id)
                SELECT * FROM (
                    SELECT opmerkingen_admin, 'App\\\\Member', id, true, 0
                      FROM members
                     WHERE opmerkingen_admin is not null
                 UNION
                    SELECT concat('©', opmerkingen_geheim), 'App\\\\Member', id, true, 0
                       FROM members
                      WHERE opmerkingen_geheim is not null
                ) x
        ");

        Schema::table("members", function ($table) {
            $table->dropColumn('opmerkingen_admin');
            $table->dropColumn('opmerkingen_geheim');
        });

        DB::statement("
            INSERT INTO comments (text, entity_type, entity_id, is_secret, user_id)
                SELECT * FROM (
                    SELECT opmerkingen_admin, 'App\\\\Participant', id, true, 0
                      FROM participants
                     WHERE opmerkingen_admin is not null
                ) x
        ");

        Schema::table("participants", function ($table) {
            $table->dropColumn('opmerkingen_admin');
        });

        DB::statement("
            INSERT INTO comments (text, entity_type, entity_id, is_secret, user_id)
                 SELECT opmerkingen, 'App\\\\Event', id, false, 0
                   FROM events
                  WHERE opmerkingen is not null
        ");

        Schema::table("events", function ($table) {
            $table->dropColumn('opmerkingen');
        });

        DB::statement("
            INSERT INTO comments (text, entity_type, entity_id, is_secret, user_id)
                 SELECT opmerkingen, 'App\\\\Location', id, false, 0
                   FROM locations
                  WHERE opmerkingen is not null
        ");
        Schema::table("locations", function ($table) {
            $table->dropColumn('opmerkingen');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table("members", function ($table) {
            $table->string('opmerkingen')->nullable();
            $table->string('opmerkingen_admin')->nullable();
            $table->string('opmerkingen_geheim')->nullable();
        });
        DB::statement("
            UPDATE members m
              LEFT JOIN comments uc on uc.entity_id = m.id and uc.entity_type = 'App\\\\Member' AND uc.user_id = 0 and uc.is_secret = false
              LEFT JOIN comments ac on ac.entity_id = m.id and ac.entity_type = 'App\\\\Member' AND ac.user_id = 0 and ac.is_secret = true and left(ac.text, 1) != '©'
              LEFT JOIN comments sc on sc.entity_id = m.id and sc.entity_type = 'App\\\\Member' AND sc.user_id = 0 and sc.is_secret = true and left(sc.text, 1) = '©'
               SET m.opmerkingen = uc.text, m.opmerkingen_admin = ac.text
        ");

        Schema::table("participants", function ($table) {
            $table->string('opmerkingen')->nullable();
            $table->string('opmerkingen_admin')->nullable();
        });
        DB::statement("
            UPDATE participants p
              LEFT JOIN comments uc on uc.entity_id = p.id and uc.entity_type = 'App\\\\Participant' AND uc.user_id = 0 and uc.is_secret = false
              LEFT JOIN comments ac on ac.entity_id = p.id and ac.entity_type = 'App\\\\Participant' AND ac.user_id = 0 and uc.is_secret = true
               SET p.opmerkingen = uc.text, p.opmerkingen_admin = ac.text
        ");

        Schema::table("events", function ($table) {
            $table->string('opmerkingen')->nullable();
        });
        DB::statement("
            UPDATE events e
              LEFT JOIN comments uc on uc.entity_id = e.id and uc.entity_type = 'App\\\\Event' AND uc.user_id = 0 and uc.is_secret = false
               SET e.opmerkingen = uc.text
        ");

        Schema::table("locations", function ($table) {
            $table->string('opmerkingen')->nullable();
        });
        DB::statement("
            UPDATE locations l
              LEFT JOIN comments uc on uc.entity_id = l.id and uc.entity_type = 'App\\\\Location' AND uc.user_id = 0 and uc.is_secret = false
               SET l.opmerkingen = uc.text
        ");

        Schema::dropIfExists('comments');
        DB::statement("DELETE FROM users WHERE id = 0");
    }
}
