<?php

declare(strict_types=1);

namespace App\Services\WebsiteUpdater;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Log;

final class WebsiteUpdaterThroughGithubActions implements WebsiteUpdater
{
    public function __construct(
        private ClientInterface $client,
        private string $githubRepo,
        private string $githubToken
    ) {
    }

    public function update(): void
    {
        $response = $this->client->request(
            'POST',
            $this->githubRepo . '/dispatches',
            [
                'body' => json_encode([
                    'event_type' => 'AAS',
                ], JSON_THROW_ON_ERROR),
                'headers' => [
                    'Accept' => 'application/vnd.github.v3+json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'bearer ' . $this->githubToken,
                ],
            ]
        );

        if ($response->getStatusCode() !== 200) {
            Log::warning(
                sprintf(
                    'Trigger of github action failed. For repo %s. Error: %s',
                    $this->githubRepo,
                    $response->getBody()->getContents()
                )
            );
        }
    }
}
