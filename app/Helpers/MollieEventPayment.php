<?php

namespace App\Helpers;

use App\Participant;
use App\Event;

class MollieEventPayment
{

    protected $mollie;
    protected $event;
    protected $participant;
    protected $participant_type = "new";
    protected $price;
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
        $this->price($event->prijs);
        return $this;
    }

    public function existing(bool $t): MollieEventPayment
    {
        $this->participant_type = $t ? "exiting" : "new";
        return $this;
    }

    public function price(float $d): MollieEventPayment
    {
        $this->price = $d;
        return $this;
    }

    public function getTotalAmount(): float
    {
        return MollieWrapper::roundPrice($this->price * $this->participant->incomeBasedDiscount());
    }

    public function getTotalAmountString(): string
    {
        return number_format($this->getTotalAmount(), 2, '.', '');
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
                "value" => $this->getTotalAmountString(),
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
