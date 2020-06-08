<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service;

use Gam6itko\OzonSeller\Service\V1\ProductsService;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

abstract class AbstractTestCase extends TestCase
{
    abstract protected function getClass(): string;

    protected function createSvc(ClientInterface $client)
    {
        $class = new \ReflectionClass($this->getClass());
        $svc = $class->newInstance(0, '', '');
        $parentClass = $class->getParentClass();
        $prop = $parentClass->getProperty('client');
        $prop->setAccessible(true);
        $prop->setValue($svc, $client);

        return $svc;
    }

    protected function createClient(string $method, string $path, array $options, string $contents): ClientInterface
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
            ->method('request')
            ->willReturnMap([
                [$method, $path, $options, $response],
            ]);

        return $client;
    }

    protected function quickTest(string $methodName, array $arguments, array $expectedRequest, string $responseJson = '{"result": []}', ?callable $fnPostRequest = null)
    {
        [$method, $path, $expectedOptions] = $expectedRequest;
        $client = $this->createClient($method, $path, $expectedOptions, $responseJson);
        /** @var ProductsService $svc */
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
