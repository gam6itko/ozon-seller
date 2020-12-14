<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service;

use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class AbstractTestCase extends TestCase
{
    abstract protected function getClass(): string;

    protected function createSvc(ClientInterface $client)
    {
        $class = new \ReflectionClass($this->getClass());

        return $class->newInstance([123, 'api-key', 'https://packagist.org/packages/gam6itko/ozon-seller'], $client);
    }

    /**
     * @param array|string|null $body
     */
    protected function createClient(string $method, string $path, $body, string $contents): ClientInterface
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects(self::once())
            ->method('getContents')
            ->willReturn($contents);

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
                self::assertEquals($body, $request->getBody()->getContents());

                return $response;
            });

        return $client;
    }

    protected function quickTest(string $methodName, array $arguments, array $expectedRequest, string $responseJson = '{"result": []}', ?callable $fnPostRequest = null)
    {
        [$method, $path, $expectedOptions] = $expectedRequest;
        $client = $this->createClient($method, $path, $expectedOptions, $responseJson);
        $svc = $this->createSvc($client);
        self::assertTrue(method_exists($svc, $methodName), "No method `$methodName`");
        $result = call_user_func_array([$svc, $methodName], $arguments);

        if ($fnPostRequest) {
            $fnPostRequest($result);

            return;
        }

        self::assertEquals(json_decode($responseJson, true)['result'], $result);
    }
}
