<?php

declare(strict_types=1);

namespace App\Services\Recaptcha;

use App\ValueObjects\RecaptchaResult\Failure;
use App\ValueObjects\RecaptchaResult\RecaptchaResult;
use App\ValueObjects\RecaptchaResult\Success;
use GuzzleHttp\ClientInterface;

final class GoogleRecaptchaV3Validator implements RecaptchaValidator
{
    private ClientInterface $client;

    private string $secret;

    public function __construct(ClientInterface $client, string $secret)
    {
        $this->client = $client;
        $this->secret = $secret;
    }

    public function validate(string $token): RecaptchaResult
    {
        try {
            $response = $this->client->request(
                'GET',
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'query' => [
                        'secret' => $this->secret,
                        'response' => $token,
                    ],
                ]
            );

            $contents = $response->getBody()->getContents();
            $responseData = json_decode($contents, true, 5, JSON_THROW_ON_ERROR);

            if (($responseData['success'] ?? false) === true) {
                return new Success();
            }

            return Failure::fromErrorCodes($responseData['error-codes'] ?? []);
        } catch (\Throwable $err) {
            return Failure::fromException($err);
        }
    }
}
