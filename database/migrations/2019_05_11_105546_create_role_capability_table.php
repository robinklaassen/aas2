<?php

declare(strict_types=1);

use App\Models\Capability;
use App\Models\Role;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoleCapabilityTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('role_capability', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('capability_id')->unsigned();

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
            [
                'role' => 'aasbaas',
                'capabilities' => [
                    'participants::info::show::basic',
                    'participants::info::show::administrative',
                    'participants::info::edit::administrative',
                    'participants::info::export',
                    'participants::account::create',
                    'participants::account::delete',
                    'members::info::show::basic',
                    'members::info::show::administrative',
                    'members::account::create',
                    'members::account::update',
                    'members::account::delete',
                    'event::show::basic',
                    'event::show::advanced',
                    'event::edit::basic',
                    'event::edit::advanced',
                    'event::create',
                    'event::delete',
                    'roles::info',
                    'roles::edit',
                    'roles::create',
                    'roles::delete',
                    'locations::info::basic',
                    'locations::info::advanced',
                    'locations::edit::basic',
                    'locations::edit::advanced',
                    'locations::create',
                    'locations::delete',

                    'courses::show',
                    'courses::create',
                    'courses::edit',
                    'courses::delete',

                ],
            ],
            [
                'role' => 'board',
                'capabilities' => [
                    'participants::info::show::basic',
                    'participants::info::show::practical',
                    'participants::info::show::private',
                    'participants::info::show::administrative',
                    'participants::account::create',
                    'participants::account::delete',
                    'participants::info::edit::basic',
                    'participants::info::edit::private',
                    'participants::info::edit::practical',
                    'participants::info::edit::administrative',
                    'participants::info::edit::password',

                    'members::old::show',
                    'members::info::show::basic',
                    'members::info::show::practical',
                    'members::info::show::administrative',
                    'members::oud::show',
                    'members::info::edit::administrative',

                    'members::info::show::special',
                    'members::info::edit::special',
                    'members::info::edit::password',

                    'members::account::create',
                    'members::account::update',
                    'members::account::delete',

                    'members::info::edit::basic',
                    'members::info::edit::private',
                    'members::info::edit::practical',
                    'members::info::edit::administrative',

                    'event::show::basic',
                    'event::show::advanced',
                    'event::show::review',
                    'event::edit::basic',
                    'event::edit::advanced',
                    'event::subjectcheck',
                    'event::create',
                    'event::delete',

                    'event::members::add',
                    'event::members::edit',
                    'event::members::remove',
                    'event::participants::add',
                    'event::participants::edit',
                    'event::participants::remove',

                    'roles::info',
                    'locations::info::basic',
                    'locations::info::advanced',
                    'locations::edit::basic',
                    'locations::edit::advanced',
                    'locations::create',
                    'locations::delete',

                    'comments::show',
                    'comments::edit',
                    'comments::create',
                    'comments::delete',
                    'comments::show::secret',
                    'comments::edit::secret',
                ],
            ],
            [
                'role' => 'president',
                'capabilities' => [
                    'participants::info::show::basic',
                    'participants::info::show::private',
                    'participants::info::show::practical',
                    'participants::account::create',
                    'participants::account::delete',

                    'members::old::show',
                    'members::info::show::basic',
                    'members::info::show::practical',
                    'members::info::show::administrative',
                    'members::oud::show',

                    'members::info::edit::basic',
                    'members::info::edit::private',
                    'members::info::edit::practical',
                    'members::info::edit::administrative',

                    'members::account::create',
                    'members::account::delete',
                    'event::show::basic',
                    'event::show::advanced',
                    'event::edit::basic',
                    'event::edit::advanced',
                    'event::create',
                    'event::delete',
                    'roles::info',
                    'roles::edit',
                    'locations::info::basic',
                    'locations::info::advanced',
                    'locations::edit::basic',
                    'locations::edit::advanced',
                    'locations::create',
                    'locations::delete',

                    'comments::show',
                    'comments::edit',
                    'comments::create',
                    'comments::delete',
                    'comments::show::secret',
                    'comments::edit::secret',
                ],
            ],
            [
                'role' => 'treasurer',
                'capabilities' => [
                    'participants::info::show::basic',
                    'participants::info::show::finance',
                    'participants::info::show::practical',
                    'participants::info::edit::finance',
                    'participants::account::create',
                    'participants::account::delete',
                    'members::old::show',
                    'members::info::show::basic',
                    'members::info::show::finance',
                    'members::info::show::practical',
                    'members::info::show::administrative',
                    'members::oud::show',
                    'members::info::edit::finance',
                    'members::info::edit::administrative',
                    'members::account::create',
                    'members::account::delete',
                    'event::show::basic',
                    'event::show::advanced',
                    'event::edit::basic',
                    'event::edit::advanced',
                    'event::budget',
                    'event::paymentoverview',
                    'event::create',
                    'event::delete',
                    'roles::info',
                    'locations::info::basic',
                    'locations::info::advanced',
                    'locations::edit::basic',
                    'locations::edit::advanced',
                    'locations::create',
                    'locations::delete',
                    'comments::show::secret',
                    'comments::edit::secret',
                ],
            ],
            [
                'role' => 'kampci',
                'capabilities' => [
                    'participants::info::show::basic',
                    'participants::info::show::practical',
                    'participants::info::show::administrative',
                    'participants::info::edit::practical',
                    'participants::info::edit::administrative',
                    'participants::info::export',
                    'participants::account::create',
                    'participants::account::delete',
                    'members::old::show',
                    'members::info::show::basic',
                    'members::info::show::private',
                    'members::info::show::practical',
                    'members::info::show::administrative',
                    'members::oud::show',
                    'members::info::edit::basic',
                    'members::info::edit::private',
                    'members::info::edit::practical',
                    'members::info::edit::administrative',

                    'event::members::add',
                    'event::members::edit',
                    'event::members::remove',

                    'members::account::create',
                    'members::account::delete',
                    'event::show::basic',
                    'event::show::advanced',
                    'event::show::review',
                    'event::edit::basic',
                    'event::edit::advanced',
                    'event::subjectcheck',
                    'event::mailing',
                    'event::nightregister',
                    'event::create',
                    'event::delete',
                    'locations::info::basic',
                    'locations::info::advanced',
                    'locations::edit::basic',
                    'locations::edit::advanced',
                    'locations::create',
                    'locations::delete',

                    'comments::create',
                    'comments::show',
                ],
            ],
            [
                'role' => 'kantoorci',
                'capabilities' => [
                    'participants::info::show::basic',
                    'participants::info::show::private',
                    'participants::info::show::practical',
                    'participants::info::show::administrative',
                    'participants::info::edit::basic',
                    'participants::info::edit::private',
                    'participants::info::edit::practical',
                    'participants::info::edit::administrative',
                    'participants::info::export',
                    'participants::account::create',
                    'participants::account::delete',
                    'members::old::show',
                    'members::info::show::basic',
                    'members::info::show::practical',
                    'members::oud::show',
                    'event::show::basic',
                    'event::show::advanced',
                    'event::show::review',
                    'event::subjectcheck',
                    'event::mailing',
                    'event::paymentoverview',
                    'event::placement',

                    'event::participants::add',
                    'event::participants::edit',
                    'event::participants::remove',

                    'locations::info::basic',

                    'comments::create',
                    'comments::show',
                ],
            ],
            [
                'role' => 'promoci',
                'capabilities' => [
                    'participants::info::show::basic',
                    'members::info::show::basic',
                    'members::old::show',
                    'event::show::basic',
                    'event::show::advanced',
                    'locations::info::basic',
                    'locations::info::advanced',

                    'comments::create',
                    'comments::show',
                ],
            ],
            [
                'role' => 'trainerci',
                'capabilities' => [
                    'participants::info::show::basic',
                    'members::info::show::basic',
                    'members::info::show::practical',
                    'event::show::basic',
                    'event::show::advanced',
                    'locations::info::basic',
                    'members::info::show::special',
                    'members::info::edit::special',

                    'comments::create',
                    'comments::show',
                ],
            ],
            [
                'role' => 'trainer',
                'capabilities' => [
                    'participants::info::show::basic',
                    'participants::info::show::practical',
                    'members::info::show::basic',
                    'members::info::show::practical',
                    'event::show::basic',
                    'event::show::advanced',
                    'locations::info::basic',
                    'locations::info::advanced',
                ],
            ],
            [
                'role' => 'member',
                'capabilities' => [
                    'members::info::edit::self',
                    'members::info::show::self',
                    'event::show::participating',

                    'event::show::basic',
                    'participants::info::show::basic',
                    'event::show::basic',
                    'locations::info::basic',
                ],
            ],
            [
                'role' => 'participant',
                'capabilities' => [
                    'event::show::participating',
                    'participants::info::show::self',
                    'participants::info::edit::self',
                ],
            ],
        ];

        $capas = Capability::all(['id', 'name'])->keyBy('name');

        foreach ($filling as $role_capa) {
            $r = Role::where('tag', '=', $role_capa['role'])->firstOrFail();
            $role_ids = array_map(function ($i) use ($capas) {
                return $capas[$i]['id'];
            }, $role_capa['capabilities']);
            $r->capabilities()->sync($role_ids);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('role_capability');
    }
}
