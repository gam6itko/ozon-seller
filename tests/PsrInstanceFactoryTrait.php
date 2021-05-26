<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Utils;
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
    protected function createClient(string $method, string $path, $body, string $responseContents): ClientInterface
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
            ->willReturnCallback(static function (RequestInterface $request) use ($method, $path, $body, $response): ResponseInterface {
                self::assertEquals($method, $request->getMethod());
                self::assertEquals($path, $request->getUri()->getPath());

                if (is_string($body)) {
                    self::assertJsonStringEqualsJsonString($body, $request->getBody()->getContents());
                } else {
                    self::assertEquals($body, $request->getBody()->getContents());
                }

                return $response;
            });

        return $client;
    }

    protected function createRequestFactory(string $methodName = 'GET', string $path = '/'): RequestFactoryInterface
    {
        $request = new Request($methodName, $path);

        $requestFactory = $this->createMock(RequestFactoryInterface::class);
        $requestFactory
            ->method('createRequest')
            ->willReturn($request);

        return $requestFactory;
    }

    protected function createStreamFactory(): StreamFactoryInterface
    {
        $streamFactory = $this->createMock(StreamFactoryInterface::class);
        $streamFactory
            ->method('createStream')
            ->willReturnCallback(static function ($body): StreamInterface {
                if (is_string($body)) {
                    return Utils::streamFor($body);
                }

                return new Stream($body);
            });

        return $streamFactory;
    }
}
