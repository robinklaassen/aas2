<?php

namespace Tests\Feature;

use Tests\TestCase;

class CourseCoverageCheckerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCourseCoverageChecker()
    {
        $user = \App\User::findOrFail(1);

        $response = $this
            ->actingAs($user)
            ->get('/events/1/check/all')
            ->assertStatus(200)
            ->assertSee('Engels')
            ->assertSee('glyphicon-ok')
            ->assertDontSee('glyphicon-alert');

        $response = $this
            ->actingAs($user)
            ->get('/events/1/check/placed')
            ->assertStatus(200);
    }
}
