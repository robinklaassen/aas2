<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Member;
use App\User;
use Illuminate\Support\Str;

class CommentTest extends TestCase
{
    use DatabaseTransactions;

    private $member;

    protected function setUp(): void
    {
        parent::setUp();
        // CommentTest::clearDB();
        $this->member = Member::findOrFail(2);
    }

    public function testDontSeeOtherNormalComment()
    {
        $user = User::findOrFail(2);

        $random = Str::random(40);
        $text = "Testing " . $random;

        $comment = new Comment();
        $comment->user_id = 1;
        $comment->text = $text;
        $comment->is_secret = false;

        $user->profile->comments()->save($comment);

        $this->actingAs($user)
            ->get("/profile")
            ->assertDontSee($comment->text);

        $this->assertDatabaseMissing(
            'comments',
            [
                "text" => $text,
                "entity_type" => "App\\Member",
                "entity_id" => "1"
            ]
        );
    }

    public function testDoesntSeeSecretComment()
    {
        $user = User::findOrFail(2);
        $comment = new Comment();

        $random = Str::random(40);
        $text = "Testing " . $random;

        $comment->user_id = 1;
        $comment->text = $text;
        $comment->is_secret = true;

        $user->profile->comments()->save($comment);

        $this->actingAs($user)
            ->get("/profile")
            ->assertDontSee($text);
    }


    public function testAdminSeesSecretComment()
    {
        $user = User::findOrFail(2);

        $comment = new Comment();
        $random = Str::random(40);
        $text = "Testing " . $random;

        $comment->user_id = 1;
        $comment->text  = $text;
        $comment->is_secret = true;

        $user->profile->comments()->save($comment);

        $this->actingAs(User::findOrFail(1))
            ->get("/members/2")
            ->assertSee($text);
    }

    public function testCreateSecretCommentAsNonSuperAdminNotAllowed()
    {

        $random = Str::random(40);
        $text = "Testing " . $random;
        $this->actingAs(User::findOrFail(2))
            ->post(
                "/comments?entity_type=App%5CMember&entity_id=2&origin=profile",
                [
                    "text" => $text,
                    "is_secret" => true
                ]
            )->assertStatus(403);

        $this->assertDatabaseMissing('comments', [
            "is_secret" => true,
            "text" => $text,
            "user_id" => 2,
            "entity_type" => "App\Member",
            "entity_id" => 2
        ]);
    }

    public function testCreateSecretCommentAsAdmin()
    {

        $random = Str::random(40);
        $text = "Testing " . $random;
        $this->actingAs(User::findOrFail(1))
            ->post(
                "/comments?entity_type=App%5CLocation&entity_id=2&origin=profile",
                [
                    "text" => $text,
                    "is_secret" => true
                ]
            )->assertRedirect('/profile');

        $this->assertDatabaseHas('comments', [
            "is_secret" => true,
            "text" => $text,
            "user_id" => 1,
            "entity_type" => "App\Location",
            "entity_id" => 2
        ]);
    }

    public function testCreateCommentAsUser()
    {
        $random = Str::random(40);
        $text = "Testing " . $random;
        $this->actingAs(User::findOrFail(2))
            ->post(
                '/comments?entity_type=App%5CEvent&entity_id=1&origin=event%2F2',
                [
                    "text" => $text,
                    "is_secret" => false
                ]
            )->assertRedirect('/event/2');

        $this->assertDatabaseHas('comments', [
            "is_secret" => false,
            "text" => $text,
            "user_id" => 2,
            "entity_type" => "App\\Event",
            "entity_id" => 1
        ]);
    }

    public function testDeleteOwnComment()
    {
        $comment = Comment::where([
            "user_id" => 2
        ])->first();

        $this->actingAs(User::findOrFail(2))
            ->delete(
                "/comments/{$comment->id}?origin=profile"
            )->assertRedirect('/profile');

        $this->assertDatabaseMissing("comments", ["id" => $comment->id]);
    }


    public function testDeleteOthersCommentNotAllowed()
    {
        $comment = Comment::where([
            "user_id" => 1
        ])->first();

        // comment shouldn't be found due to the CommentScope
        $this->actingAs(User::findOrFail(2))
            ->delete(
                "/comments/{$comment->id}?origin=profile"
            )->assertStatus(404);

        $this->assertDatabaseHas("comments", ["id" => $comment->id]);
    }

    public function testDeleteOthersCommentAsAdmin()
    {
        $comment = Comment::where([
            "user_id" => 2
        ])->first();

        $this->actingAs(User::findOrFail(1))
            ->delete(
                "/comments/{$comment->id}?origin=profile"
            )->assertRedirect('/profile');

        $this->assertDatabaseMissing("comments", ["id" => $comment->id]);
    }

    public function testEditOwnComment()
    {
        $comment = Comment::where([
            "user_id" => 2
        ])->first();

        $random = Str::random(40);
        $text = "Testing " . $random;
        $this->actingAs(User::findOrFail(2))
            ->patch(
                "/comments/{$comment->id}?origin=profile",
                [
                    "text" => $text
                ]
            )->assertRedirect('/profile');

        $this->assertDatabaseHas("comments", ["id" => $comment->id, "text" => $text]);
    }

    public function testEditOthersCommentNotAllowed()
    {
        $comment = Comment::where([
            "user_id" => 2
        ])->first();

        $random = Str::random(40);
        $text = "Testing " . $random;
        $this->actingAs(User::findOrFail(1))
            ->patch(
                "/comments/{$comment->id}?origin=profile",
                [
                    "text" => $text
                ]
            )->assertStatus(403);

        $this->assertDatabaseMissing("comments", ["id" => $comment->id, "text" => $text]);
    }


    public function testEditCommentToSecretNotAllowed()
    {
        $comment = Comment::where([
            "user_id" => 2,
            "is_secret" => false
        ])->first();

        $random = Str::random(40);
        $text = "Testing " . $random;
        $this->actingAs(User::findOrFail(2))
            ->patch(
                "/comments/{$comment->id}?origin=profile",
                [
                    "text" => $text,
                    "is_secret" => true
                ]
            )->assertStatus(403);

        $this->assertDatabaseMissing("comments", ["id" => $comment->id, "text" => $text]);
        $this->assertDatabaseHas("comments", ["id" => $comment->id, "is_secret" => false]);
    }


    public function testEditCommentToSecretAsAdmin()
    {
        $comment = Comment::where([
            "user_id" => 1,
            "is_secret" => false
        ])->first();

        $random = Str::random(40);
        $text = "Testing " . $random;
        $this->actingAs(User::findOrFail(1))
            ->patch(
                "/comments/{$comment->id}?origin=profile",
                [
                    "text" => $text,
                    "is_secret" => true
                ]
            )->assertRedirect('/profile');

        $this->assertDatabaseHas("comments", ["id" => $comment->id, "is_secret" => true, "text" => $text]);
    }
}
