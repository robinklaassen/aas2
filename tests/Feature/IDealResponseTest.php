<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Facades\Mollie;
use App\Mail\participants\IDealConfirmation;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mollie\Api\Resources\Payment;
use Tests\TestCase;

class FakePaymentMetadata
{
    public $type = 'existing';

    public $camp_id;

    public $participant_id;

    public function __construct(Event $evt, Participant $part)
    {
        $this->camp_id = $evt->id;
        $this->participant_id = $part->id;
    }
}

class FakeEventPayment extends Payment
{
    public $metadata;

    public function __construct(Event $evt, Participant $part)
    {
        parent::__construct(Mollie::api());
        $this->metadata = new FakePaymentMetadata($evt, $part);
    }

    public function isPaid()
    {
        return true;
    }
}

class IDealResponseTest extends TestCase
{
    use DatabaseTransactions;

    public $event;

    public $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->event = Event::findOrFail(5);
        $this->user = User::findOrFail(3);
    }

    public function testFailedIDealResponse()
    {
        Mail::fake();
        DB::insert(
            '
            insert into event_participant
                    (event_id, participant_id)
             values (?, ?)
            ',
            [
                $this->event->id,
                $this->user->profile->id,
            ]
        );
        $response = $this->get(action('iDealController@eventPaymentResponse', [$this->user->profile, $this->event]));

        $response->assertStatus(200);
        // See payment status
        $response->assertSee('iDeal betaling mislukt');
    }

    public function testSucessvolIDealResponse()
    {
        DB::statement(
            '
            insert into event_participant
                    (event_id, participant_id, datum_betaling, geplaatst)
             values (?, ?, ?, 0)
            ',
            [
                $this->event->id,
                $this->user->profile->id,
                '2022-12-02',
            ]
        );
        $response = $this->get(action('iDealController@eventPaymentResponse', [$this->user->profile, $this->event]));
        $response->assertStatus(200);
        // See payment status
        $response->assertSee('U heeft uw kind succesvol ingeschreven');
    }

    public function testIDealWebhook()
    {
        Mail::fake();
        Mail::assertNothingSent();

        DB::statement(
            '
            insert into event_participant
                    (event_id, participant_id)
             values (?, ?)
            ',
            [
                $this->event->id,
                $this->user->profile->id,
            ]
        );

        $someId = '010DEADBEEF010';
        $fakepayment = new FakeEventPayment($this->event, $this->user->profile);

        $mock = Mollie::fakePayments();
        $mock->shouldReceive('get')->with($someId)->once()->andReturns($fakepayment);

        $response = $this->post(action('iDealController@webhook'), [
            'id' => $someId,
        ]);
        $response->assertStatus(200);

        Mail::assertSent(IDealConfirmation::class);

        $this->assertDatabaseHas('event_participant', [
            'event_id' => $this->event->id,
            'participant_id' => $this->user->profile->id,
            'datum_betaling' => date('Y-m-d'),
        ]);
    }
}
