<?php
namespace App\Helpers\Payment;

interface PaymentProvider
{
    public function process(PaymentInterface $payment);
}
