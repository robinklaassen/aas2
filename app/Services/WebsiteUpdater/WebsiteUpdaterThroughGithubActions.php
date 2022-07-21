<?php

declare(strict_types=1);

namespace App\Services\WebsiteUpdater;

use GuzzleHttp\ClientInterface as HttpClientInterface;
use RuntimeException;

final class WebsiteUpdaterThroughGithubActions implements WebsiteUpdater
{
    private const BASE_URI = 'https://api.github.com/repos/';

    public function __construct(
        private HttpClientInterface $httpClient,
        private string $githubRepo,
        private string $githubToken
    ) {
    }

    public function update(): void
    {
        $response = $this->httpClient->request(
            'POST',
            self::BASE_URI . $this->githubRepo . '/dispatches',
            [
                'body' => json_encode([
                    'event_type' => 'AAS',
                ], JSON_THROW_ON_ERROR),
                'headers' => [
                    'Accept' => 'application/vnd.github.v3+json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'bearer ' . $this->githubToken,
                ],
                'http_errors' => false,
            ]
        );

        if ($response->getStatusCode() > 299 || $response->getStatusCode() < 200) {
            throw new RuntimeException(
                sprintf(
                    'Trigger of github action failed with status %d. For repo %s. Error: %s',
                    $response->getStatusCode(),
                    $this->githubRepo,
                    $response->getBody()->getContents()
                )
            );
        }
    }
}
