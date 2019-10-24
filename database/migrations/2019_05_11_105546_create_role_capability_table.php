<?php

use App\Capability;
use App\Role;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRoleCapabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_capability', function (Blueprint $table) {
            $table->integer("role_id")->unsigned();
            $table->integer("capability_id")->unsigned();


            $table->foreign('role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->foreign('capability_id')
                ->references('id')
                ->on('capabilities')
                ->onDelete('cascade');
        });



        $filling = [
            ["role" => "aasbaas",  "capabilities" => [
                "participants::info::show::basic",
                "participants::info::show::administrative",
                "participants::info::edit::administrative",
                "participants::info::export",
                "participants::account::create",
                "participants::account::delete",
                "members::info::show::basic",
                "members::info::show::administrative",
                "members::account::create",
                "members::account::delete",
                "event::show::basic",
                "event::show::advanced",
                "event::edit::basic",
                "event::edit::advanced",
                "event::create",
                "event::delete",
                "roles::info",
                "roles::edit",
                "roles::create",
                "roles::delete",
                "locations::info::basic",
                "locations::info::advanced",
                "locations::edit::basic",
                "locations::edit::advanced",
                "locations::create",
                "locations::delete"
            ]],
            ["role" => "board", "capabilities" => [
                "participants::info::show::basic",
                "participants::info::show::practical",
                "participants::account::create",
                "participants::account::delete",
                "members::old::show",
                "members::info::show::basic",
                "members::info::show::practical",
                "members::info::show::administrative",
                "members::info::edit::administrative",
                "members::account::create",
                "members::account::delete",
                "event::show::basic",
                "event::show::advanced",
                "event::edit::basic",
                "event::edit::advanced",
                "event::subjectcheck",
                "event::create",
                "event::delete",
                "roles::info",
                "locations::info::basic",
                "locations::info::advanced",
                "locations::edit::basic",
                "locations::edit::advanced",
                "locations::create",
                "locations::delete",
                "comments::show::secret",
                "comments::edit::secret"
            ]],
            ["role" => "president", "capabilities" => [
                "participants::info::show::basic",
                "participants::info::show::practical",
                "participants::account::create",
                "participants::account::delete",
                "members::old::show",
                "members::info::show::basic",
                "members::info::show::practical",
                "members::info::show::administrative",
                "members::info::edit::administrative",
                "members::account::create",
                "members::account::delete",
                "event::show::basic",
                "event::show::advanced",
                "event::edit::basic",
                "event::edit::advanced",
                "event::create",
                "event::delete",
                "roles::info",
                "roles::edit",
                "locations::info::basic",
                "locations::info::advanced",
                "locations::edit::basic",
                "locations::edit::advanced",
                "locations::create",
                "locations::delete",
                "comments::show::secret",
                "comments::edit::secret"
            ]],
            ["role" => "treasurer", "capabilities" => [
                "participants::info::show::basic",
                "participants::info::show::finance",
                "participants::info::show::practical",
                "participants::info::edit::finance",
                "participants::account::create",
                "participants::account::delete",
                "members::old::show",
                "members::info::show::basic",
                "members::info::show::finance",
                "members::info::show::practical",
                "members::info::show::administrative",
                "members::info::edit::finance",
                "members::info::edit::administrative",
                "members::account::create",
                "members::account::delete",
                "event::show::basic",
                "event::show::advanced",
                "event::edit::basic",
                "event::edit::advanced",
                "event::budget",
                "event::paymentoverview",
                "event::create",
                "event::delete",
                "roles::info",
                "locations::info::basic",
                "locations::info::advanced",
                "locations::edit::basic",
                "locations::edit::advanced",
                "locations::create",
                "locations::delete",
                "comments::show::secret",
                "comments::edit::secret"
            ]],
            ["role" => "kampci", "capabilities" => [
                "participants::info::show::basic",
                "participants::info::show::practical",
                "participants::info::show::administrative",
                "participants::info::edit::practical",
                "participants::info::edit::administrative",
                "participants::info::export",
                "participants::account::create",
                "participants::account::delete",
                "members::old::show",
                "members::info::show::basic",
                "members::info::show::private",
                "members::info::show::practical",
                "members::info::show::administrative",
                "members::info::edit::basic",
                "members::info::edit::private",
                "members::info::edit::practical",
                "members::info::edit::administrative",
                "members::account::create",
                "members::account::delete",
                "event::show::basic",
                "event::show::advanced",
                "event::edit::basic",
                "event::edit::advanced",
                "event::subjectcheck",
                "event::mailing",
                "event::nightregister",
                "event::create",
                "event::delete",
                "locations::info::basic",
                "locations::info::advanced",
                "locations::edit::basic",
                "locations::edit::advanced",
                "locations::create",
                "locations::delete"
            ]],
            ["role" => "kantoorci", "capabilities" => [
                "participants::info::show::basic",
                "participants::info::show::private",
                "participants::info::show::practical",
                "participants::info::show::administrative",
                "participants::info::edit::basic",
                "participants::info::edit::private",
                "participants::info::edit::practical",
                "participants::info::edit::administrative",
                "participants::info::export",
                "participants::account::create",
                "participants::account::delete",
                "members::old::show",
                "members::info::show::basic",
                "members::info::show::practical",
                "event::show::basic",
                "event::show::advanced",
                "event::subjectcheck",
                "event::mailing",
                "event::paymentoverview",
                "event::placement",
                "locations::info::basic"
            ]],
            ["role" => "promoci", "capabilities" => [
                "participants::info::show::basic",
                "members::info::show::basic",
                "members::old::show",
                "event::show::basic",
                "event::show::advanced",
                "locations::info::basic",
                "locations::info::advanced"
            ]],
            ["role" => "trainerci", "capabilities" => [
                "participants::info::show::basic",
                "members::info::show::basic",
                "members::info::show::practical",
                "event::show::basic",
                "event::show::advanced",
                "locations::info::basic"
            ]],
            ["role" => "trainer", "capabilities" => [
                "participants::info::show::basic",
                "participants::info::show::practical",
                "members::info::show::basic",
                "members::info::show::practical",
                "event::show::basic",
                "event::show::advanced",
                "locations::info::basic",
                "locations::info::advanced"
            ]],
            ["role" => "member", "capabilities" => [
                "members::info::edit::self",
                "members::info::show::self",
                "event::show::participating",                

                "event::show::basic",
                "participants::info::show::basic",
                "event::show::basic",
                "locations::info::basic"
            ]],
            ["role" => "member-see-others", "capabilities" => [
                "members::info::show::basic",
                "event::show::basic",
                "locations::info::basic"
            ]],
            ["role" => "old-member", "capabilities" => [
            ]],
            ["role" => "participant", "capabilities" => [
                "event::show::participating",
                "participants::info::show::self",
                "participants::info::edit::self"
            ]],
        ];

        $capas = Capability::all(["id", "name"])->keyBy("name");

        foreach($filling as $role_capa) {
            $r = Role::where("tag", "=", $role_capa["role"])->firstOrFail();
            $role_ids = array_map(function($i) use ($capas) {
                return $capas[$i]["id"];
            }, $role_capa["capabilities"]);
            $r->capabilities()->sync($role_ids);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_capability');
    }
}
