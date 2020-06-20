<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service;

use Gam6itko\OzonSeller\Exception\BadRequestException;
use Gam6itko\OzonSeller\Exception\OzonSellerException;
use Gam6itko\OzonSeller\Service\V1\ProductsService;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 *
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\AbstractService
 */
class ServiceTest extends TestCase
{
    /**
     * @dataProvider dataConstructor
     */
    public function testConstructor(array $config, array $expected)
    {
        $client = $this->createMock(ClientInterface::class);
        $svc = new ProductsService($config, $client);
        $class = new \ReflectionClass($svc);
        $parent = $class->getParentClass();
        $prop = $parent->getProperty('config');
        $prop->setAccessible(true);
        self::assertEquals($expected, $prop->getValue($svc));
    }

    public function dataConstructor()
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
        new ProductsService([0, 1, 2, 3, 4], $client);
    }

    public function testThrowEmptyConfig()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Not defined mandatory config parameters `clientId` or `apiKey`');

        $client = $this->createMock(ClientInterface::class);
        new ProductsService([], $client);
    }

    public function testBadResponseWithoutThrow()
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('API method /v1/product/delete is unavailable');

        $jsonError = '{"error":{"code":"BAD_REQUEST","message":"API method /v1/product/delete is unavailable","data":[]}}';
        $client = $this->createMockClientWithErrorResponse($jsonError);
        $svc = new ProductsService([1, 'a'], $client);
        $svc->importInfo(123);
    }

    public function testThrowUnknownErrorCode(): void
    {
        $this->expectException(OzonSellerException::class);
        $this->expectExceptionMessage('{"error":{"code":"YOU_dont_kNOw_me_","message":"your test will fall!","data":[]}}');

        $jsonError = '{"error":{"code":"YOU_dont_kNOw_me_","message":"your test will fall!","data":[]}}';
        $client = $this->createMockClientWithErrorResponse($jsonError);
        $svc = new ProductsService([1, 'a'], $client);
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
}
