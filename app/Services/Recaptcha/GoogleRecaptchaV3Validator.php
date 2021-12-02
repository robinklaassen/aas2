<?php

declare(strict_types=1);

namespace App\Services\Recaptcha;

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

    public function validate(string $token): bool
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

            return ($responseData['success'] ?? false) === true;
        } catch (\Throwable $err) {
            return false;
        }
    }
}
