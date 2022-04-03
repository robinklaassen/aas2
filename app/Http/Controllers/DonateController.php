<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\Payment\Donation;
use App\Helpers\Payment\PaymentProvider;
use App\Http\Requests\DonationRequest;

final class DonateController
{
    private PaymentProvider $paymentProvider;

    public function __construct(PaymentProvider $paymentProvider)
    {
        $this->paymentProvider = $paymentProvider;
    }

    public function __invoke(DonationRequest $donationRequest)
    {
        $donation = new Donation($donationRequest->amount(), $donationRequest->name());
        return $this->paymentProvider->process($donation);
    }
}
