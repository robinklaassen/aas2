<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Helpers\Payment\EventPayment;
use App\Helpers\Payment\MolliePaymentProvider;
use App\Models\Event;
use App\Models\EventPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

class ParticipantProfileOnCampTest extends TestCase
{
    use WithoutMiddleware;

    use DatabaseTransactions;

    protected $data = [
        'selected_camp' => 5,
        'vak' => [3, 1],
        'vakinfo' => ['Daarom', 'Ich sprechen keine niederlandisch'],
        'iDeal' => 0,
    ];

    protected $event;

    protected $user;

    protected $package;

    protected function setUp(): void
    {
        parent::setUp();
        $this->event = Event::findOrFail($this->data['selected_camp']);
        $this->user = User::findOrFail(3); // Annabelle, we zijn niets zonder jou, Annabelle!
    }

    /**
     * Tests to send a participant on camp from the profile page without iDeal
     */
    public function testOnCampWithoutIDeal()
    {
        Mail::fake();

        $this->instance(MolliePaymentProvider::class, Mockery::mock(MolliePaymentProvider::class, function ($mock) {
            $mock->shouldNotReceive('process');
        }));

        $this->data['iDeal'] = 0;
        $response = $this->actingAs($this->user)->put(action('ProfileController@onCampSave'), $this->data);

        $response->assertStatus(302);
        $response->assertRedirect(action('ProfileController@show'));
        $response->assertSessionHas('flash_message', 'Uw kind is aangemeld voor kamp!');
    }

    /**
     * Tests to send a participant on camp from the profile page with iDeal
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
                    return $contains($this->event->code, $descr)
                        && $contains($this->user->profile->voornaam, $descr)
                        && $contains($this->user->profile->achternaam, $descr);
                }))
                ->andReturns(redirect('https://mollie-backend'));
        }));

        $this->data['iDeal'] = 1;

        $response = $this->actingAs($this->user)->put(action('ProfileController@onCampSave'), $this->data);

        $response->assertStatus(302);
        $response->assertRedirect('https://mollie-backend');

        $this->assertDatabaseHas(
            'event_participant',
            [
                'event_id' => 5,
                'participant_id' => $this->user->profile->id,
            ]
        );
    }

    public function testOnCampWithPackageWithIDeal()
    {
        Mail::fake();

        // Event with online packages
        $this->event = Event::findOrFail(7);
        $this->package = EventPackage::findOrFail(2);
        $this->data['selected_camp'] = $this->event->id;
        $this->data['selected_package'] = $this->package->id;
        $this->data['iDeal'] = '1';

        $this->instance(MolliePaymentProvider::class, Mockery::mock(MolliePaymentProvider::class, function ($mock) {
            $mock->shouldReceive('process')
                ->once()
                ->with(Mockery::on(function (EventPayment $arg) {
                    $contains = function ($needle, $haystack) {
                        return strpos($haystack, $needle) !== false;
                    };

                    $descr = $arg->getDescription();

                    return $contains($this->event->code, $descr)
                        && $contains($this->package->code, $descr)
                        && $contains($this->user->profile->voornaam, $descr)
                        && $contains($this->user->profile->achternaam, $descr);
                }))
                ->andReturns(redirect('https://mollie-backend'));
        }));

        $response = $this->actingAs($this->user)->put(action('ProfileController@onCampSave'), $this->data);

        $response->assertStatus(302);
        $response->assertRedirect('https://mollie-backend');

        $this->assertDatabaseHas(
            'event_participant',
            [
                'event_id' => $this->event->id,
                'participant_id' => $this->user->profile->id,
                'package_id' => $this->package->id,
            ]
        );
    }
}
