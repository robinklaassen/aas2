<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Member;
use App\Participant;
use App\User;

class UserTableSeeder extends Seeder
{

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
		$user->privacy = '2018-06-01';
		$member->user()->save($user);

		$member = Member::find(2);
		$user = new User;
		$user->username = 'jon';
		$user->password = bcrypt('snow');
		$user->is_admin = 0;
		$user->privacy = '2018-06-01';
		$member->user()->save($user);

		$participant = Participant::find(2);
		$user = new User;
		$user->username = 'annabelle';
		$user->password = bcrypt('zomers');
		$user->is_admin = 0;
		$participant->user()->save($user);

		$member = Member::find(3);
		$user = new User;
		$user->username = 'dkrijgsman';
		$user->password = bcrypt('poep');
		$user->is_admin = 0;
		$member->user()->save($user);
	}
}
