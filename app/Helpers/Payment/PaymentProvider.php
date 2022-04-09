<?php

declare(strict_types=1);

namespace App\Helpers\Payment;

interface PaymentProvider
{
    public function process(PaymentInterface $payment): string;
}
