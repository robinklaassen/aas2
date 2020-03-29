<?php

namespace App\Helpers\Payment;

use \Mockery;
use App\Helpers\Payment\PaymentProvider;
use App\Helpers\Payment\PaymentInterface;
use Illuminate\Support\Facades\App;

class MolliePaymentProvider implements PaymentProvider
{

    protected $mollie;

    public function __construct()
    {
        $this->mollie = new \Mollie\Api\MollieApiClient();
        $this->mollie->setApiKey(config('mollie.api_key'));
    }

    public function api()
    {
        return $this->mollie;
    }

    public function fakeApi()
    {
        $this->mollie = Mockery::mock($this->mollie);
        return $this->mollie;
    }

    public function fakePayments()
    {
        $this->mollie->payments = Mockery::mock($this->mollie->payments);
        return $this->mollie->payments;
    }

    public function process(PaymentInterface $payment)
    {
        $keyString = implode('/', $payment->getKeys());
        $p = $this->api()->payments->create(array(
            "amount"      => [
                "currency" => $payment->getCurrency(),
                "value" => number_format($payment->getTotalAmount(), 2, '.', '')
            ],
            "description" => $payment->getDescription(),
            "metadata"    => $payment->getMetadata(),
            "webhookUrl"  => App::environment("local") ? null : url('iDeal-webhook'),
            "redirectUrl" => url("iDeal-response/{$keyString}"),
            "method" =>  \Mollie\Api\Types\PaymentMethod::IDEAL
        ));

        return redirect($p->getCheckoutUrl());
    }
}
