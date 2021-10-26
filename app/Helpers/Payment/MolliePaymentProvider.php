<?php

declare(strict_types=1);

namespace App\Helpers\Payment;

use Illuminate\Support\Facades\App;
use Mockery;

class MolliePaymentProvider implements PaymentProvider
{
    protected $mollie;

    protected $isTesting = false;

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
        $this->isTesting = true;
        return $this->mollie;
    }

    public function fakePayments()
    {
        $this->mollie->payments = Mockery::mock($this->mollie->payments);
        $this->isTesting = true;
        return $this->mollie->payments;
    }

    public function process(PaymentInterface $payment)
    {
        $keyString = implode('/', $payment->getKeys());
        $p = $this->api()->payments->create([
            'amount' => [
                'currency' => $payment->getCurrency(),
                'value' => number_format($payment->getTotalAmount(), 2, '.', ''),
            ],
            'description' => $payment->getDescription(),
            'metadata' => $payment->getMetadata(),
            'webhookUrl' => $this->webhookUrl(),
            'redirectUrl' => url("iDeal-response/{$keyString}"),
            'method' => \Mollie\Api\Types\PaymentMethod::IDEAL,
        ]);

        return redirect($p->getCheckoutUrl());
    }

    public function webhookUrl(): ?string
    {
        return ! App::environment('local') || $this->isTesting ? url('iDeal-webhook') : null;
    }
}
