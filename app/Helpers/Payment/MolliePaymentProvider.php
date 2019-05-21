<?php
namespace App\Helpers\Payment;

use App\Helpers\Payment\PaymentProvider;
use App\Helpers\Payment\PaymentInterface;

class MolliePaymentProvider implements PaymentProvider
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

    public function process(PaymentInterface $payment)
    {
        $keyString = implode('/', $payment->getKeys);
        $p = $this->mollie->api()->payments->create(array(
            "amount"      => [
                "currency" => $payment->getCurrency(),
                "value" => number_format($payment->getTotalAmount(), 2, '.', '')
            ],
            "description" => $payment->getDescription(),
            "metadata"    => $payment->getMetadata(),
            "webhookUrl"  => url('iDeal-webhook'),
            "redirectUrl" => url("iDeal-response/{$keyString}"),
            "method" =>  \Mollie\Api\Types\PaymentMethod::IDEAL
        ));

        return redirect($p->getCheckoutUrl());
    }
}
