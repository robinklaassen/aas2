<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Review;
use DB;
use Illuminate\Database\Seeder;

class MemberReviewPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('member_review')->delete();

        $review = Review::find(1);
        $review->members()->attach([
            1 => [
                'stof' => 4,
                'aandacht' => 2,
                'tevreden' => 3,
                'mening' => 4,
                'bericht' => 'Je bent het allerleukste spookje dat er bestaat!',
            ],
        ]);
    }
}
