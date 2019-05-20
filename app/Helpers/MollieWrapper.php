<?php
namespace App\Helpers;


class MollieWrapper
{
    const CURRENCY = "EUR";
    protected $mollie;

    public function __construct()
    {
        $this->mollie = new \Mollie\Api\MollieApiClient();
        $this->mollie->setApiKey(env('MOLLIE_API_KEY'));
    }

    public function api()
    {
        return $this->mollie;
    }

    public function eventPayment(): MollieEventPayment
    {
        return new MollieEventPayment($this);
    }
}
