<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Location;
use App\Models\Member;
use DB;
use Illuminate\Database\Seeder;

class CommentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('comments')->delete();

        $member = Member::find(1);
        $comment = new Comment();
        $comment->user_id = 1;
        $comment->text = 'Hoi ik ben een spookje!';
        $comment->is_secret = false;
        $member->comments()->save($comment);

        $member = Member::find(2);
        $comment = new Comment();
        $comment->user_id = 2;
        $comment->text = 'Ik verdedig deze muur! van kennis?';
        $comment->is_secret = false;
        $member->comments()->save($comment);

        $member = Member::find(2);
        $comment = new Comment();
        $comment->user_id = 1;
        $comment->text = 'Goed bezig jon';
        $comment->is_secret = false;
        $member->comments()->save($comment);

        $member = Member::find(2);
        $comment = new Comment();
        $comment->user_id = 1;
        $comment->text = 'Hij weet echt niets!';
        $comment->is_secret = true;
        $member->comments()->save($comment);

        $location = Location::find(1);
        $comment = new Comment();
        $comment->user_id = 1;
        $comment->text = 'Erg mooie bosrijke locatie, maar slecht bereikbaar met het OV.';
        $location->comments()->save($comment);

        $member = Member::find(4);
        $comment = new Comment();
        $comment->user_id = 1;
        $comment->text = 'Serieus, waar is deze vent vandaan gekomen?!';
        $comment->is_secret = false;
        $member->comments()->save($comment);
    }
}
