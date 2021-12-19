<?php

declare(strict_types=1);

namespace App\ValueObjects\RecaptchaResult;

final class Failure implements RecaptchaResult
{
    private string $message;

    private function __construct(string $message)
    {
        $this->message = $message;
    }

    public static function fromErrorCodes(array $errorCodes): self
    {
        if (count($errorCodes) === 0) {
            return self::unknown();
        }

        return new self('Recaptcha failed with the following error-codes: ' . implode(', ', $errorCodes));
    }

    public static function unknown(): self
    {
        return new self('An unknown error occurred during validation');
    }

    public static function fromException(\Throwable $err): self
    {
        return new self('An error occurred during validation: ' . $err->getMessage());
    }

    public function isValid(): bool
    {
        return false;
    }

    public function message(): string
    {
        return $this->message;
    }
}
