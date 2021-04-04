<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use App\Skill;

class SkillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('skills')->delete();

        $seeded_skills = ['programmeren', 'LaTeX', 'humor'];

        foreach ($seeded_skills as $skill) {
            Skill::create([
                'tag' => $skill
            ]);
        }
    }
}
