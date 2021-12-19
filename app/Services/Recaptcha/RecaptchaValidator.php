<?php

declare(strict_types=1);

namespace App\Services\Recaptcha;

use App\ValueObjects\RecaptchaResult\RecaptchaResult;

interface RecaptchaValidator
{
    public function validate(string $token): RecaptchaResult;
}
