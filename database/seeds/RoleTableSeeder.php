<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;

class RoleTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('roles')->delete();

		Role::create([
			'title' => 'AAS-Baas',
			'tag' => 'admin+'
        ]);

		Role::create([
			'title' => 'Bestuur',
			'tag' => 'admin'
        ]);

		Role::create([
			'title' => 'KampCI',
			'tag' => 'kamp-ci'
        ]);

	}

}
