<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service;

use Gam6itko\OzonSeller\Exception\BadRequestException;
use Gam6itko\OzonSeller\Exception\OzonSellerException;
use Gam6itko\OzonSeller\Service\V1\ProductService;
use Gam6itko\OzonSeller\Tests\PsrInstanceFactoryTrait;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Utils;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\HttpClient\Psr18Client;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 *
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\AbstractService
 */
class ServiceTest extends TestCase
{
    use PsrInstanceFactoryTrait;

    /**
     * @dataProvider dataConstructor
     */
    public function testConstructor(array $config, array $expected)
    {
        $client = $this->createMock(ClientInterface::class);
        $svc = new ProductService($config, $client, $this->createRequestFactory(), $this->createStreamFactory());
        $class = new \ReflectionClass($svc);
        $parent = $class->getParentClass();
        $prop = $parent->getProperty('config');
        $prop->setAccessible(true);
        self::assertEquals($expected, $prop->getValue($svc));
    }

    public function dataConstructor(): iterable
    {
        yield [
            [
                '123',
                'api-key',
                'https://github.com/gam6itko/ozon-seller',
            ],
            [
                'clientId' => '123',
                'apiKey'   => 'api-key',
                'host'     => 'https://github.com',
            ],
        ];

        yield [
            [
                'clientId' => '123',
                'apiKey'   => 'api-key',
                'host'     => 'https://github.com',
            ],
            [
                'clientId' => '123',
                'apiKey'   => 'api-key',
                'host'     => 'https://github.com',
            ],
        ];

        yield [
            [
                'clientId' => '123',
                'apiKey'   => 'api-key',
                'host'     => 'https://github.com',
                'foo'      => 1,
                'bar'      => 2,
                'baz'      => 3,
            ],
            [
                'clientId' => '123',
                'apiKey'   => 'api-key',
                'host'     => 'https://github.com',
            ],
        ];

        // default host
        yield [
            [
                '123',
                'api-key',
            ],
            [
                'clientId' => '123',
                'apiKey'   => 'api-key',
                'host'     => 'https://api-seller.ozon.ru',
            ],
        ];
    }

    public function testThrowToManyConfigParams()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('To many config parameters');

        $client = $this->createMock(ClientInterface::class);
        new ProductService([0, 1, 2, 3, 4], $client);
    }

    public function testThrowEmptyConfig()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Not defined mandatory config parameters `clientId` or `apiKey`');

        $client = $this->createMock(ClientInterface::class);
        new ProductService([], $client);
    }

    public function testBadResponseWithoutThrow(): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('API method /v1/product/delete is unavailable');

        $jsonError = '{"error":{"code":"BAD_REQUEST","message":"API method /v1/product/delete is unavailable","data":[]}}';
        $client = $this->createMockClientWithErrorResponse($jsonError);
        $svc = new ProductService([1, 'a'], $client, $this->createRequestFactory(), $this->createStreamFactory());
        $svc->importInfo(123);
    }

    public function testThrowUnknownErrorCode(): void
    {
        $this->expectException(OzonSellerException::class);
        $this->expectExceptionMessage('{"error":{"code":"YOU_dont_kNOw_me_","message":"your test will fall!","data":[]}}');

        $jsonError = '{"error":{"code":"YOU_dont_kNOw_me_","message":"your test will fall!","data":[]}}';
        $client = $this->createMockClientWithErrorResponse($jsonError);
        $svc = new ProductService([1, 'a'], $client, $this->createRequestFactory(), $this->createStreamFactory());
        $svc->importInfo(123);
    }

    private function createMockClientWithErrorResponse(string $json): ClientInterface
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->method('getContents')
            ->willReturn($json);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->method('getBody')
            ->willReturn($stream);
        $response
            ->method('getStatusCode')
            ->willReturn(400);

        $client = $this->createMock(ClientInterface::class);
        $client
            ->method('sendRequest')
            ->willReturn($response);

        return $client;
    }

    public function testSymfonyPsr18Client(): void
    {
        $client = $this->createMock(Psr18Client::class);
        $client
            ->expects(self::atLeastOnce())
            ->method('createRequest')
            ->willReturnCallback(static function ($method, $url): RequestInterface {
                return new Request($method, $url);
            });
        $client
            ->expects(self::atLeastOnce())
            ->method('createStream')
            ->willReturnCallback(static function ($body): StreamInterface {
                if (is_string($body)) {
                    return Utils::streamFor($body);
                }

                return new Stream($body);
            });
        $client
            ->expects(self::once())
            ->method('sendRequest')
            ->willReturnCallback(static function (): ResponseInterface {
                return new Response(200, [], '{"result":"hello"}');
            });
        $svc = new ProductService([1, 'a'], $client);
        $result = $svc->info(123);
    }
}
