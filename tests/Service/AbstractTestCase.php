<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service;

use Gam6itko\OzonSeller\Tests\PsrInstanceFactoryTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

abstract class AbstractTestCase extends TestCase
{
    use PsrInstanceFactoryTrait;

    abstract protected function getClass(): string;

    protected function createSvc(ClientInterface $client, RequestFactoryInterface $requestFactory, StreamFactoryInterface $streamFactory)
    {
        $class = new \ReflectionClass($this->getClass());

        return $class->newInstance(
            [123, 'api-key', 'https://packagist.org/packages/gam6itko/ozon-seller'],
            $client,
            $requestFactory,
            $streamFactory
        );
    }

    protected function quickTest(string $methodName, array $arguments, array $expectedRequest, string $responseJson = '{"result": []}', ?callable $fnPostRequest = null)
    {
        [$method, $path, $expectedOptions] = $expectedRequest;
        $client = $this->createClient($method, $path, $expectedOptions, $responseJson);
        $requestFactory = $this->createRequestFactory();
        $streamFactory = $this->createStreamFactory();
        $svc = $this->createSvc($client, $requestFactory, $streamFactory);
        self::assertTrue(method_exists($svc, $methodName), "No method `$methodName`");
        $result = call_user_func_array([$svc, $methodName], $arguments);

        if ($fnPostRequest) {
            $fnPostRequest($result);

            return;
        }

        self::assertEquals(json_decode($responseJson, true)['result'], $result);
    }
}
