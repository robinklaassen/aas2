<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Participant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->delete();

        DB::statement("INSERT INTO users (id, username, password, profile_id, profile_type, is_admin) VALUES ('0', 'SYS', '', 0, 'App\\\\Models\\\\Member', true)");

        $member = Member::find(1);
        $user = new User();
        $user->username = 'ranonkeltje';
        $user->password = bcrypt('ranonkeltje');
        $user->is_admin = 2;
        $user->save();
        $user->privacy = '2018-06-01';
        $member->user()->save($user);
        $roles = Role::whereIn('tag', ['aasbaas', 'president', 'board', 'member', 'treasurer'])->get();
        $user->roles()->sync($roles);

        $member = Member::find(2);
        $user = new User();
        $user->username = 'jon';
        $user->password = bcrypt('snow');
        $user->is_admin = 0;
        $user->save();
        $user->is_admin = 1;
        $user->privacy = '2018-06-01';
        $member->user()->save($user);
        $roles = Role::whereIn('tag', ['kantoorci', 'member'])->get();
        $user->roles()->sync($roles);

        $participant = Participant::find(2);
        $user = new User();
        $user->username = 'annabelle';
        $user->password = bcrypt('zomers');
        $user->is_admin = 0;
        $participant->user()->save($user);

        $roles = Role::whereIn('tag', ['participant'])->get();
        $user->roles()->sync($roles);

        $member = Member::find(3);
        $user = new User();
        $user->username = 'dkrijgsman';
        $user->password = bcrypt('poep');
        $user->is_admin = 0;
        $user->privacy = '2018-06-01';
        $member->user()->save($user);

        $roles = Role::whereIn('tag', ['member'])->get();
        $user->roles()->sync($roles);

        $member = Member::find(5);
        $user = new User();
        $user->username = 'penningmeester';
        $user->password = bcrypt('penningmeester');
        $user->is_admin = 0;
        $user->privacy = '2018-06-01';
        $member->user()->save($user);

        $roles = Role::whereIn('tag', ['member', 'treasurer', 'board'])->get();
        $user->roles()->sync($roles);

        $member = Member::find(6);
        $user = new User();
        $user->username = 'kampci';
        $user->password = bcrypt('kampci');
        $user->is_admin = 0;
        $user->privacy = '2018-06-01';
        $member->user()->save($user);

        $roles = Role::whereIn('tag', ['member', 'kampci'])->get();
        $user->roles()->sync($roles);

        $member = Member::find(7);
        $user = new User();
        $user->username = 'aasbaas';
        $user->password = bcrypt('aasbaas');
        $user->is_admin = 0;
        $user->privacy = '2018-06-01';
        $member->user()->save($user);

        $roles = Role::whereIn('tag', ['member', 'aasbaas'])->get();
        $user->roles()->sync($roles);

        $member = Member::find(8);
        $user = new User();
        $user->username = 'promoci';
        $user->password = bcrypt('promoci');
        $user->is_admin = 0;
        $user->privacy = '2018-06-01';
        $member->user()->save($user);

        $roles = Role::whereIn('tag', ['member', 'promoci'])->get();
        $user->roles()->sync($roles);

        $member = Member::find(9);
        $user = new User();
        $user->username = 'kantoorci';
        $user->password = bcrypt('kantoorci');
        $user->is_admin = 0;
        $user->privacy = '2018-06-01';
        $member->user()->save($user);

        $roles = Role::whereIn('tag', ['member', 'kantoorci'])->get();
        $user->roles()->sync($roles);

        $member = Member::find(10);
        $user = new User();
        $user->username = 'redacteur';
        $user->password = bcrypt('redacteur');
        $user->is_admin = 0;
        $user->privacy = '2018-06-01';
        $member->user()->save($user);

        $roles = Role::whereIn('tag', ['member', 'ranonkeltje'])->get();
        $user->roles()->sync($roles);

        $member = Member::find(11);
        $user = new User();
        $user->username = 'vertrouwen';
        $user->password = bcrypt('vertrouwen');
        $user->is_admin = 0;
        $user->privacy = '2018-06-01';
        $member->user()->save($user);

        $roles = Role::whereIn('tag', ['member', 'counselor'])->get();
        $user->roles()->sync($roles);

        $participant = Participant::find(4);
        $user = new User();
        $user->username = 'henk';
        $user->password = bcrypt('henk');
        $user->is_admin = 0;
        $user->privacy = '2018-06-01';
        $participant->user()->save($user);

        $roles = Role::whereIn('tag', ['participant'])->get();
        $user->roles()->sync($roles);

        $participant = Participant::find(5);
        $user = new User();
        $user->username = 'jan';
        $user->password = bcrypt('janssen');
        $user->is_admin = 0;
        $user->privacy = '2018-06-01';
        $participant->user()->save($user);

        $roles = Role::whereIn('tag', ['participant'])->get();
        $user->roles()->sync($roles);
    }
}
