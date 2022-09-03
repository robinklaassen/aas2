<?php

declare(strict_types=1);

namespace App\Services\WebsiteUpdater;

use GuzzleHttp\ClientInterface as HttpClientInterface;
use RuntimeException;

final class WebsiteUpdaterThroughWebhook implements WebsiteUpdater
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $uri,
    ) {
    }

    public function update(): void
    {
        $response = $this->httpClient->request(
            'POST',
            $this->uri,
        );

        if ($response->getStatusCode() > 299 || $response->getStatusCode() < 200) {
            throw new RuntimeException(
                sprintf(
                    'Trigger of webhook failed with status %d. Error: %s',
                    $response->getStatusCode(),
                    $response->getBody()->getContents()
                )
            );
        }
    }
}
