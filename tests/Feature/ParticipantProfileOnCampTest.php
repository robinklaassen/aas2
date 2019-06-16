<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\User;
use App\Helpers\Payment\MolliePaymentProvider;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Event;
use App\Helpers\Payment\EventPayment;


class ParticipantProfileOnCampTest extends TestCase
{
    use WithoutMiddleware;


    protected $data = [
        "selected_camp" => 5,
        "vak" => [3, 1],
        "vakinfo" => ["Daarom", "Ich sprechen keine niederlandisch"],
        "iDeal" => 0
    ];
    protected $event;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->event = Event::findOrFail($this->data["selected_camp"]);

        DB::statement("
            delete from event_participant where event_id = 5
        ");
        // annabel, we zijn niets zonder jou, annabel!
        $this->user = User::findOrFail(3);
    }

    protected function tearDown(): void
    {
        DB::statement("
            delete from event_participant where event_id = 5
        ");
        parent::tearDown();
    }

    /**
     * Tests to send a participant on camp from the profile page without ideal
     *
     * @return void
     */
    public function testOnCampWithoutIDeal()
    {
        Mail::fake();

        $this->instance(MolliePaymentProvider::class, Mockery::mock(MolliePaymentProvider::class, function ($mock) {
            $mock->shouldNotReceive('process');
        }));

        $this->data["iDeal"] = 0;
        $response = $this->actingAs($this->user)->put(action("ProfileController@onCampSave"), $this->data);

        $response->assertStatus(302);
        $response->assertRedirect(action('ProfileController@show'));
        $response->assertSessionHas("flash_message", 'Uw kind is aangemeld voor kamp!');
    }

    /**
     * Tests to send a participant on camp from the profile page with ideal
     *
     * @return void
     */
    public function testOnCampWithIDeal()
    {
        Mail::fake();

        $this->instance(MolliePaymentProvider::class, Mockery::mock(MolliePaymentProvider::class, function ($mock) {
            $mock->shouldReceive('process')
                ->once()
                ->with(Mockery::on(function (EventPayment $arg) {

                    $contains = function ($needle, $haystack) {
                        return strpos($haystack, $needle) !== false;
                    };

                    $descr = $arg->getDescription();
                    // dd($descr);
                    return $contains($this->event->code, $descr)
                        && $contains($this->user->profile->voornaam, $descr)
                        && $contains($this->user->profile->achternaam, $descr);
                }))
                ->andReturns(redirect("https://mollie-backend"));
        }));

        $this->data["iDeal"] = 1;
        $response = $this->actingAs($this->user)->put(action("ProfileController@onCampSave"), $this->data);

        $response->assertStatus(302);
        $response->assertRedirect("https://mollie-backend");

        $this->assertDatabaseHas(
            'event_participant',
            [
                "event_id" => 5,
                "participant_id" => $this->user->profile->id
            ]
        );
    }
}
