<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Comments extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('text');
            $table->boolean('is_secret');
            $table->string('entity_type');
            $table->integer('entity_id')->unsigned();
            $table->integer('user_id')->unsigned();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        // PLEASE NOTE: the below migrations require a 'SYS' user with ID 0
        // There was a DB statement here to insert one but due to problems on production it was moved to the UserTableSeeder

        // Migrate the member comments
        DB::statement("
            INSERT INTO comments (text, entity_type, entity_id, is_secret, user_id)
                SELECT * FROM (
                    SELECT opmerkingen_admin, 'App\\\\Member', id, false, 0
                      FROM members
                     WHERE opmerkingen_admin is not null
                 UNION
                    SELECT opmerkingen_geheim, 'App\\\\Member', id, true, 0
                       FROM members
                      WHERE opmerkingen_geheim is not null
                ) x
        ");

        Schema::table('members', function ($table) {
            $table->dropColumn('opmerkingen_admin');
            $table->dropColumn('opmerkingen_geheim');
        });

        // Migrate the participant comments
        DB::statement("
            INSERT INTO comments (text, entity_type, entity_id, is_secret, user_id)
                SELECT * FROM (
                    SELECT opmerkingen_admin, 'App\\\\Participant', id, false, 0
                      FROM participants
                     WHERE opmerkingen_admin is not null
                ) x
        ");

        Schema::table('participants', function ($table) {
            $table->dropColumn('opmerkingen_admin');
        });

        // Migrate the event comments
        DB::statement("
            INSERT INTO comments (text, entity_type, entity_id, is_secret, user_id)
                 SELECT opmerkingen, 'App\\\\Event', id, false, 0
                   FROM events
                  WHERE opmerkingen is not null
        ");

        Schema::table('events', function ($table) {
            $table->dropColumn('opmerkingen');
        });

        // Migrate the location comments
        DB::statement("
            INSERT INTO comments (text, entity_type, entity_id, is_secret, user_id)
                 SELECT opmerkingen, 'App\\\\Location', id, false, 0
                   FROM locations
                  WHERE opmerkingen is not null
        ");
        Schema::table('locations', function ($table) {
            $table->dropColumn('opmerkingen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {

        // Return the member comments
        Schema::table('members', function ($table) {
            $table->string('opmerkingen_admin')->nullable();
            $table->string('opmerkingen_geheim')->nullable();
        });
        DB::statement("
            UPDATE members m
              LEFT JOIN comments ac on ac.entity_id = m.id and ac.entity_type = 'App\\\\Member' AND ac.user_id = 0 and ac.is_secret = false
              LEFT JOIN comments sc on sc.entity_id = m.id and sc.entity_type = 'App\\\\Member' AND sc.user_id = 0 and sc.is_secret = true
               SET m.opmerkingen_admin = ac.text, m.opmerkingen_geheim = sc.text
        ");

        // Return the participant comments
        Schema::table('participants', function ($table) {
            $table->string('opmerkingen_admin')->nullable();
        });
        DB::statement("
            UPDATE participants p
              LEFT JOIN comments ac on ac.entity_id = p.id and ac.entity_type = 'App\\\\Participant' AND ac.user_id = 0
               SET p.opmerkingen_admin = ac.text
        ");

        // Return the event comments
        Schema::table('events', function ($table) {
            $table->string('opmerkingen')->nullable();
        });
        DB::statement("
            UPDATE events e
              LEFT JOIN comments uc on uc.entity_id = e.id and uc.entity_type = 'App\\\\Event' AND uc.user_id = 0
               SET e.opmerkingen = uc.text
        ");

        // Return the location comments
        Schema::table('locations', function ($table) {
            $table->string('opmerkingen')->nullable();
        });
        DB::statement("
            UPDATE locations l
              LEFT JOIN comments uc on uc.entity_id = l.id and uc.entity_type = 'App\\\\Location' AND uc.user_id = 0
               SET l.opmerkingen = uc.text
        ");

        Schema::dropIfExists('comments');

        // Remove the SYS user
        DB::statement('DELETE FROM users WHERE id = 0');
    }
}
