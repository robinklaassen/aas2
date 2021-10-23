<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call([
            MemberTableSeeder::class,
            LocationTableSeeder::class,
            EventTableSeeder::class,
            CourseTableSeeder::class,
            EventMemberPivotSeeder::class,
            CourseMemberPivotSeeder::class,
            ParticipantTableSeeder::class,
            EventParticipantPivotSeeder::class,
            CourseEventParticipantPivotSeeder::class,
            UserTableSeeder::class,
            ActionTableSeeder::class,
            ReviewTableSeeder::class,
            MemberReviewPivotSeeder::class,
            CommentTableSeeder::class,
            SkillTableSeeder::class,
            MemberSkillPivotSeeder::class
        ]);
    }
}
