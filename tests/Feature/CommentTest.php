<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Member;
use App\User;

class CommentTest extends TestCase
{
    use DatabaseTransactions;

    // private static function clearDB()
    // {
    //     DB::statement("Delete from comments where entity_type = 'App\\Member' and entity_id = 3");
    // }

    private $member;

    protected function setUp(): void
    {
        parent::setUp();
        // CommentTest::clearDB();
        $this->member = Member::findOrFail(2);
    }

    // protected function tearDown(): void
    // {
    //     CommentTest::clearDB();
    //     parent::tearDown();
    // }

    public function testSeeNormalComment()
    {
        $user = User::findOrFail(2);
        // This doesnt work for some reason
        // $this->actingAs($user)
        //     ->get("/profile")
        //     ->assertDontSee("This is a testing comment");

        $random = Str::random(40);
        $text = "Testing " . $random;

        $comment = new Comment();
        $comment->user_id = 1;
        $comment->text = $text;
        $comment->is_secret = false;

        $user->profile->comments()->save($comment);

        $this->actingAs($user)
            ->get("/profile")
            ->assertSee($comment->text);

        $this->assertDatabaseHas(
            'comments',
            [
                "text" => $text,
                "entity_type" => "App\\Member",
                "entity_id" => "2"
            ]
        );
    }


    // public function testDoesntSeeSecretComment()
    // {
    //     $user = User::findOrFail(2);
    //     $this->actingAs($user)
    //         ->get("/members/2")
    //         ->assertDontSee("This is a testing comment");

    //     $comment = new Comment();

    //     $comment->user_id = 1;
    //     $comment->text = "This is a testing comment";
    //     $comment->is_secret = true;

    //     $this->member->comments()->save($comment);

    //     $this->actingAs($user)
    //         ->get("/members/2")
    //         ->assertSee("This is a testing comment");
    // }


    // public function testAdminSeesSecretComment()
    // {
    //     $user = User::findOrFail(1);
    //     $this->actingAs($user)
    //         ->get("/members/2")
    //         ->assertDontSee("This is a testing comment");

    //     $comment = new Comment();

    //     $comment->user_id = 1;
    //     $comment->text = "This is a testing comment";
    //     $comment->is_secret = true;

    //     $this->member->comments()->save($comment);

    //     $this->actingAs($user)
    //         ->get("/members/2")
    //         ->assertSee("This is a testing comment");
    // }
}
