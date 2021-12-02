<?php

declare(strict_types=1);

namespace App\Services\Recaptcha;

interface RecaptchaValidator
{
    public function validate(string $token): bool;
}
