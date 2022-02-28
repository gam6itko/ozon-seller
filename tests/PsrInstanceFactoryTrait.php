<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests;

use GuzzleHttp\Psr7\Stream;
use Http\Factory\Guzzle\RequestFactory;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

trait PsrInstanceFactoryTrait
{
    /**
     * @param array|string|null $body
     */
    protected function createClient(string $expectedMethod, string $expectedPath, $body, string $responseContents): ClientInterface
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects(self::once())
            ->method('getContents')
            ->willReturn($responseContents);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects(self::once())
            ->method('getBody')
            ->willReturn($stream);

        $client = $this->createMock(ClientInterface::class);
        $client
            ->expects(self::once())
            ->method('sendRequest')
            ->willReturnCallback(static function (RequestInterface $request) use ($expectedMethod, $expectedPath, $body, $response): ResponseInterface {
                self::assertEquals($expectedMethod, $request->getMethod());
                self::assertEquals($expectedPath, $request->getUri()->getPath());

                if (is_string($body)) {
                    self::assertJsonStringEqualsJsonString($body, $request->getBody()->getContents());
                } else {
                    self::assertEquals($body, $request->getBody()->getContents());
                }

                return $response;
            });

        return $client;
    }

    protected function createRequestFactory(): RequestFactoryInterface
    {
        return new RequestFactory();
    }

    protected function createStreamFactory(): StreamFactoryInterface
    {
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $streamFactory
            ->method('createStream')
            ->willReturnCallback(static function ($body): StreamInterface {
                if (is_string($body)) {
                    $stream = fopen('php://temp', 'r+');
                    if ('' !== $stream) {
                        fwrite($stream, $body);
                        fseek($stream, 0);
                    }

                    return new Stream($stream);
                }

                return new Stream($body);
            });

        return $streamFactory;
    }
}
