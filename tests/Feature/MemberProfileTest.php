<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Member;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MemberProfileTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->member = Member::findOrFail(2);  // jön snow
    }

    public function testEditProfile()
    {
        $fields = $this->member->toArray();
        $fields['rijbewijs'] = 1;

        $response = $this->actingAs($this->member->user)->patch(
            action('ProfileController@update'),
            $fields
        );

        $response->assertStatus(302);
        $response->assertRedirect(action('ProfileController@show'));
        $response->assertSessionHas('flash_message', 'Je profiel is bewerkt!');

        $this->member->refresh();
        $this->assertSame(1, $this->member->rijbewijs);
    }

    public function testAddCourse()
    {
        Mail::fake();

        $response = $this->actingAs($this->member->user)->put(
            action('ProfileController@addCourseSave'),
            [
                'selected_course' => 1,
                // nederlands
                'klas' => 6,
            ]
        );

        $response->assertStatus(302);
        $response->assertRedirect(action('ProfileController@show'));
        $response->assertSessionHas('flash_message', 'Vak toegevoegd!');
    }
}
