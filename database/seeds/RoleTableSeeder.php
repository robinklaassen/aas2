<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Role;
use App\Capability;

class RoleTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('roles')->delete();

		$r = Role::create([
			'title' => 'AAS-Baas',
			'tag' => 'admin+'
		]);
		$r->capabilities()->sync(Capability::all());

		$r = Role::create([
			'title' => 'Bestuur',
			'tag' => 'admin'
		]);
		$r->capabilities()->sync(Capability::all());

		$r = Role::create([
			'title' => 'KampCI',
			'tag' => 'kamp-ci'
		]);
		$r->capabilities()->sync(
			Capability::findByNames([
				'export-event-participants',
				'create-event',
				'edit-event',
				'show-member-kmg',
			])
		);

		$r = Role::create([
			'title' => 'KantoorCI',
			'tag' => 'kantoor-ci'
		]);

		$r->capabilities()->sync(
			Capability::findByNames([
				'edit-participant',
			])
		);
	}
}
