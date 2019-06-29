<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Comment;
use App\Member;

class CommentTest extends TestCase
{

    private static function clearDB()
    {
        DB::statement("Delete from comments where entity_type = 'App\\Member' and entity_id = 3");
    }

    private $member;

    protected function setUp(): void
    {
        parent::tearDown();
        CommentTest::clearDB();
        $this->member = Member::findOrFail(3);
    }

    protected function tearDown(): void
    {
        CommentTest::clearDB();
        parent::tearDown();
    }

    public function testNormalComment()
    {
        $user = User::findOrFail(2);
        // $this->actingAs($user)
        //     ->visit("/comments/")
    }
}
