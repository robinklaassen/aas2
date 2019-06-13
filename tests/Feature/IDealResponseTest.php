<?php

namespace Tests\Feature;

use App\Event;
use App\User;
use App\Participant;
use App\Facades\Mollie;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\participants\IDealConfirmation;

class FakePaymentMetadata
{
    public $type = "existing";
    public $camp_id;
    public $participant_id;

    function __construct(Event $evt, Participant $part)
    {
        $this->camp_id = $evt->id;
        $this->participant_id = $part->id;
    }
}
class FakeEventPayment
{
    public $metadata;
    function __construct(Event $evt, Participant $part)
    {
        $this->metadata = new FakePaymentMetadata($evt, $part);
    }

    function isPaid()
    {
        return true;
    }
}



class IDealResponse extends TestCase
{

    public $event;
    public $user;


    protected function setUp(): void
    {
        parent::setUp();
        $this->event = Event::findOrFail(5);

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


    public function testFailedIDealResponse()
    {
        Mail::fake();
        DB::insert(
            "
            insert into event_participant
                    (event_id, participant_id)
             values (?, ?)
            ",
            [
                $this->event->id,
                $this->user->profile->id
            ]
        );
        $response = $this->get(action('iDealController@response', [$this->user->profile, $this->event]));

        $response->assertStatus(200);
        // See payment status
        $response->assertSee("iDeal betaling mislukt");
    }

    public function testSucessvolIDealResponse()
    {
        DB::statement(
            "
            insert into event_participant
                    (event_id, participant_id, datum_betaling, geplaatst)
             values (?, ?, ?, 0)
            ",
            [
                $this->event->id,
                $this->user->profile->id,
                '2022-12-02'
            ]
        );
        $response = $this->get(action('iDealController@response', [$this->user->profile, $this->event]));
        $response->assertStatus(200);
        // See payment status
        $response->assertSee("U heeft uw kind succesvol ingeschreven");
    }

    public function testIDealWebhook()
    {
        Mail::fake();
        Mail::assertNothingSent();

        DB::statement(
            "
            insert into event_participant
                    (event_id, participant_id)
             values (?, ?)
            ",
            [
                $this->event->id,
                $this->user->profile->id
            ]
        );

        $someId = "010DEADBEEF010";
        $fakepayment = new FakeEventPayment($this->event, $this->user->profile);

        $mock = Mollie::fakePayments();
        $mock->shouldReceive("get")->with($someId)->once()->andReturns($fakepayment);

        $response = $this->post(action('iDealController@webhook'), ["id" => $someId]);
        $response->assertStatus(200);
        Mail::assertSent(IDealConfirmation::class);

        $this->assertDatabaseHas('event_participant', [
            "event_id" => $this->event->id,
            "participant_id" => $this->user->profile->id,
            "datum_betaling" => date('Y-m-d')
        ]);
    }
}
