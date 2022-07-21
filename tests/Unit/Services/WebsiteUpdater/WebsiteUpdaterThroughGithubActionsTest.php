<?php

declare(strict_types=1);

namespace Tests\Unit\Services\WebsiteUpdater;

use App\Services\WebsiteUpdater\WebsiteUpdaterThroughGithubActions;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Hamcrest\Arrays\IsArrayContainingKeyValuePair;
use Hamcrest\Core\AllOf;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use RuntimeException;

final class WebsiteUpdaterThroughGithubActionsTest extends MockeryTestCase
{
    public const REPOSITORY = ':repository:';

    public const TOKEN = ':token:';

    private ClientInterface|MockInterface $client;

    private WebsiteUpdaterThroughGithubActions $subject;

    protected function setUp(): void
    {
        $this->client = Mockery::mock(ClientInterface::class);
        $this->subject = new WebsiteUpdaterThroughGithubActions(
            $this->client,
            self::REPOSITORY,
            self::TOKEN,
        );
    }

    public function testItCallsGithub(): void
    {
        $response = new Response(204);
        $this->client
            ->expects('request')
            ->with(
                'POST',
                self::REPOSITORY . '/dispatches',
                AllOf::allOf(
                    IsArrayContainingKeyValuePair::hasKeyValuePair('body', json_encode([
                        'event_type' => 'AAS',
                    ])),
                    IsArrayContainingKeyValuePair::hasKeyValuePair(
                        'headers',
                        IsArrayContainingKeyValuePair::hasKeyValuePair('Authorization', 'bearer ' . self::TOKEN)
                    ),
                ),
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
