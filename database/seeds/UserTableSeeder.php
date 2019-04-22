<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Member;
use App\Participant;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->delete();

		$member = Member::find(1);
		$user = new User;
		$user->username = 'ranonkeltje';
		$user->password = bcrypt('ranonkeltje');
		$user->is_admin = 2;
		$user->save();
		$user->roles()->attach(Role::find(1));
		$member->user()->save($user);

		$member = Member::find(2);
		$user = new User;
		$user->username = 'jon';
		$user->password = bcrypt('snow');
		$user->is_admin = 0;
		$user->save();
		$user->roles()->attach(Role::find(2));
		$user->roles()->attach(Role::find(3));
		$member->user()->save($user);

		$participant = Participant::find(2);
		$user = new User;
		$user->username = 'annabelle';
		$user->password = bcrypt('zomers');
		$user->is_admin = 0;
		$participant->user()->save($user);

	}

}
