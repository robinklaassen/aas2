<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Skill;
use DB;
use Illuminate\Database\Seeder;

class SkillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('skills')->delete();

        $seeded_skills = ['programmeren', 'LaTeX', 'humor'];

        foreach ($seeded_skills as $skill) {
            Skill::create([
                'tag' => $skill,
            ]);
        }
    }
}
