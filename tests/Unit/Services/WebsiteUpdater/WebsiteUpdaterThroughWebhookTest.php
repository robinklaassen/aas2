<?php

declare(strict_types=1);

namespace Tests\Unit\Services\WebsiteUpdater;

use App\Services\WebsiteUpdater\WebsiteUpdaterThroughWebhook;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Hamcrest\Text\StringContains;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use RuntimeException;

final class WebsiteUpdaterThroughWebhookTest extends MockeryTestCase
{
    public const URI = ':uri:';

    private ClientInterface|MockInterface $client;

    private WebsiteUpdaterThroughWebhook $subject;

    protected function setUp(): void
    {
        $this->client = Mockery::mock(ClientInterface::class);
        $this->subject = new WebsiteUpdaterThroughWebhook(
            $this->client,
            self::URI,
        );
    }

    public function testItCallsToURI(): void
    {
        $response = new Response(200);
        $this->client
            ->expects('request')
            ->with(
                'POST',
                StringContains::containsString(self::URI),
            )
            ->andReturn($response);

        $this->subject->update();
    }

    public function testItHandlesNon200StatusCodes(): void
    {
        $response = new Response(404);
        $this->client
            ->expects('request')
            ->andReturn($response);

        $this->expectException(RuntimeException::class);

        $this->subject->update();
    }
}
