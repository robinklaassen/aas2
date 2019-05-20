<?php

use App\Participant;
use App\Event;

class MollieEventPayment
{

    protected $mollie;
    protected $event;
    protected $participant;
    protected $participant_type = "new";
    protected $amount;
    protected $currency = "EUR";

    public function __construct(MollieWrapper $mollie)
    {
        $this->mollie = $mollie;
    }

    public function participant(Participant $participant): MollieEventPayment
    {
        $this->participant = $participant;
        return $this;
    }


    public function event(Event $event): MollieEventPayment
    {
        $this->event = $event;
        return $this;
    }

    public function existing(bool $t): MollieEventPayment
    {
        $this->participant_type = $t ? "exiting" : "new";
        return $this;
    }

    public function amount(float $d): MollieEventPayment
    {
        $this->amount = $d;
        return $this;
    }

    public function finalize()
    {

        if (!isset($this->event)) {
            throw new InvalidArgumentException("No event given for payment");
        }

        if (!isset($this->participant)) {
            throw new InvalidArgumentException("No participant given for payment");
        }

        $descr = $this->event->code . " - " . str_replace("  ", " ", $this->participant->voornaam . " " . $this->participant->tussenvoegsel . " " . $this->participant->achternaam);

        return $this->mollie->api()->payments->create(array(
            "amount"      => [
                "currency" => $this->currency,
                "value" => $this->amount * $this->participant->incomeBasedDiscount()
            ],
            "description" => $descr,
            "metadata"      => array(
                "participant_id" => $this->participant->id,
                "camp_id" => $this->event->id,
                "type" => $this->participant_type
            ),
            "webhookUrl"  => url('iDeal-webhook'),
            "redirectUrl" => url("iDeal-response/{$this->participant->id}/{$this->event->id}"),
            "method" =>  \Mollie\Api\Types\PaymentMethod::IDEAL
        ));
    }
}
