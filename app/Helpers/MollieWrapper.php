<?php
namespace App\Helpers;


class MollieWrapper {
    const CURRENCY = "EUR";
    protected $mollie;

    public function __construct() {
        $this->mollie = new \Mollie\Api\MollieApiClient();
        $this->mollie->setApiKey(env('MOLLIE_KEY'));
    }

    public function api() {
        return $this->mollie;
    }

    public function eventPayment(): MolliePayment {
        return new MollieEventPayment($this);
    }

}
