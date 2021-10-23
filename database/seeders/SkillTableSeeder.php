<?php

namespace Database\Seeders;

use App\Skill;
use DB;
use Illuminate\Database\Seeder;

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
