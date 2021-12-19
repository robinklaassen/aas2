<?php

declare(strict_types=1);

namespace App\ValueObjects\RecaptchaResult;

final class Success implements RecaptchaResult
{
    public function isValid(): bool
    {
        return true;
    }

    public function message(): string
    {
        return 'Recaptcha is valid';
    }
}
