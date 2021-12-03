<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Recaptcha;

use App\Services\Recaptcha\GoogleRecaptchaV3Validator;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

final class GoogleRecaptchaV3ValidatorTest extends MockeryTestCase
{
    private const SECRET = ':secret:';

    private const TOKEN = ':token:';

    private GoogleRecaptchaV3Validator $subject;

    /**
     * @var ClientInterface|Mockery\MockInterface
     */
    private $mockClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = Mockery::mock(ClientInterface::class);
        $this->subject = new GoogleRecaptchaV3Validator($this->mockClient, self::SECRET);
    }

    public static function validResponseDataProvider(): \Generator
    {
        yield 'success: true' => [true];
        yield 'success: false' => [false];
    }

    /**
     * @dataProvider validResponseDataProvider
     */
    public function testValidateHappyPath(bool $success): void
    {
        $this->expectRequestResponse(new Response(200, [], json_encode([
            'success' => $success,
        ])));

        $result = $this->subject->validate(self::TOKEN);

        self::assertSame($success, $result);
    }

    public function testValidateReturnsFalseOnError(): void
    {
        $this->expectRequestException(new \Exception(':error:'));

        $result = $this->subject->validate(self::TOKEN);

        self::assertFalse($result);
    }

    private function expectRequestResponse(Response $response): void
    {
        $this->mockClient
            ->expects('request')
            ->with(
                'GET',
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'query' => [
                        'secret' => self::SECRET,
                        'response' => self::TOKEN,
                    ],
                ]
            )->andReturn($response);
    }

    private function expectRequestException(\Exception $exception): void
    {
        $this->mockClient
            ->expects('request')
            ->with(
                'GET',
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'query' => [
                        'secret' => self::SECRET,
                        'response' => self::TOKEN,
                    ],
                ]
            )->andThrow($exception);
    }
}
