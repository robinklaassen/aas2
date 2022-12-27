<?php

declare(strict_types=1);

namespace Tests\Unit\Services\DirectAdmin;

use App\Services\DirectAdmin\ClientUsingGuzzle;
use App\Services\DirectAdmin\ValueObjects\Command;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PHPUnit\Framework\Assert;

final class ClientUsingGuzzleTest extends MockeryTestCase
{
    private string $user;

    private string $token;

    private ClientInterface|Mockery\MockInterface $client;

    private ClientUsingGuzzle $subject;

    protected function setUp(): void
    {
        $this->user = ':user:';
        $this->token = ':token:';
        $this->client = Mockery::mock(ClientInterface::class);
        $this->subject = new ClientUsingGuzzle($this->user, $this->token, $this->client);
    }

    public function testExecuteQueryString(): void
    {
        $this->client->expects('request')
            ->with('GET', '/CMD?json=no', [
                'auth' => [$this->user, $this->token],
                'http_errors' => false,
            ])
            ->andReturn(new Response(200));

        $command = Command::create('CMD');

        $this->subject->executeQueryString($command);
    }

    public function testExecuteJson(): void
    {
        $this->client->expects('request')
            ->with('GET', '/CMD?json=yes', [
                'auth' => [$this->user, $this->token],
                'http_errors' => false,
            ])
            ->andReturn(new Response(200, [], '{"test":"x"}'));

        $command = Command::create('CMD');

        $result = $this->subject->executeJson($command);

        Assert::assertEquals([
            'test' => 'x',
        ], $result);
    }

    public function testExecuteWithBody(): void
    {
        $this->client->expects('request')
            ->with('POST', '/CMD?json=no', [
                'auth' => [$this->user, $this->token],
                'http_errors' => false,
                'body' => '{"test":"x"}',
            ])
            ->andReturn(new Response(200, [], 'str'));

        $command = Command::create('CMD')->asPost([
            'test' => 'x',
        ]);

        $result = $this->subject->executeQueryString($command);

        Assert::assertSame('str', $result);
    }

    public function testExecuteThrowsExceptionsOnNon200Response(): void
    {
        $this->client->expects('request')
            ->with('GET', '/CMD?json=no', [
                'auth' => [$this->user, $this->token],
                'http_errors' => false,
            ])
            ->andReturn(new Response(500, [], 'An error message'));

        $command = Command::create('CMD');

        $this->expectException(\Exception::class);
        $this->expectWarningMessage('Invalid response from DirectAdmin, got 500: An error message');

        $this->subject->executeQueryString($command);
    }
}
