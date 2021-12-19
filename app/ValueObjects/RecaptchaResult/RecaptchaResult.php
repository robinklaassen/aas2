<?php

declare(strict_types=1);

namespace App\ValueObjects\RecaptchaResult;

interface RecaptchaResult
{
    public function isValid(): bool;

    public function message(): string;
}
