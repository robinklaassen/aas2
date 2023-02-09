<?php

declare(strict_types=1);

namespace App\Services\DirectAdmin;

use App\Services\DirectAdmin\Contracts\Client;
use App\Services\DirectAdmin\ValueObjects\Command;
use GuzzleHttp\ClientInterface;

final class ClientUsingGuzzle implements Client
{
    public function __construct(
        private string $username,
        private string $accessToken,
        private ClientInterface $client
    ) {
    }

    public function executeQueryString(Command $command): string
    {
        return $this->execute($command, false);
    }

    public function executeJson(Command $command): array
    {
        $body = $this->execute($command, true);

        return json_decode($body, true, 512, JSON_THROW_ON_ERROR);
    }

    private function execute(Command $command, bool $asJson): string
    {
        $options = [
            'auth' => [$this->username, $this->accessToken],
            'http_errors' => false,
        ];
        if ($command->body() !== null) {
            $options['body'] = json_encode($command->body(), JSON_THROW_ON_ERROR);
        }
        $response = $this->client->request(
            $command->method(),
            sprintf('/%s?json=%s', strtoupper($command->command()), $asJson ? 'yes' : 'no'),
            $options,
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception(sprintf('Invalid response from DirectAdmin, got %d: %s', $response->getStatusCode(), $response->getBody()->getContents()));
        }

        return $response->getBody()->getContents();
    }
}
