<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service;

use Gam6itko\OzonSeller\Exception\AccessDeniedException;
use Gam6itko\OzonSeller\Exception\BadRequestException;
use Gam6itko\OzonSeller\Exception\NotFoundException;
use Gam6itko\OzonSeller\Exception\OzonSellerException;
use Gam6itko\OzonSeller\Service\V2\Posting\FbsService;
use Http\Client\Exception\HttpException;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ExceptionTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return FbsService::class;
    }

    /**
     * @dataProvider dataProvider
     */
    public function test(string $class, string $json, array $expectedData = []): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects(self::once())
            ->method('getContents')
            ->willReturn($json);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects(self::once())
            ->method('getBody')
            ->willReturn($stream);

        $exception = $this->createMock(HttpException::class);
        $exception
            ->expects(self::once())
            ->method('getResponse')
            ->willReturn($response);

        $client = $this->createMock(ClientInterface::class);
        $client
            ->expects(self::once())
            ->method('sendRequest')
            ->willThrowException($exception);

        try {
            /** @var FbsService $svc */
            $svc = $this->createSvc($client, $this->createRequestFactory(), $this->createStreamFactory());
            $svc->get('');
        } catch (OzonSellerException $exc) {
            self::assertInstanceOf($class, $exc);
            self::assertEquals($expectedData, $exc->getDetails());
            self::assertTrue(false !== strpos((string) $exc, 'Data:'));
        }
    }

    public function dataProvider(): iterable
    {
        yield [
            AccessDeniedException::class,
            '{"error":{"code":"ACCESS_DENIED","message":"Invalid Api-Key, please contact support","data":[]}}',
        ];

        yield [
            NotFoundException::class,
            '{"error":{"code":"NOT_FOUND_ERROR","message":"POSTING_NOT_FOUND","data":[]}}',
        ];

        yield [
            BadRequestException::class,
            '{"error":{"code":"BAD_REQUEST","message":"Invalid request payload","data":[{"name":"posting_number","code":"EMPTY","value":"","message":""}]}}',
            [
                ['name' => 'posting_number', 'code' => 'EMPTY', 'value' => '', 'message' => ''],
            ],
        ];
    }

    public function test502(): void
    {
        $content = <<<HTML
<html>
<head><title>502 Bad Gateway</title></head>
<body>
<center><h1>502 Bad Gateway</h1></center>
<hr><center>nginx</center>
</body>
</html>
HTML;

        $this->expectException(OzonSellerException::class);
        $this->expectExceptionMessage($content);

        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects(self::once())
            ->method('getContents')
            ->willReturn($content);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(502);
        $response
            ->expects(self::once())
            ->method('getBody')
            ->willReturn($stream);

        $client = $this->createMock(ClientInterface::class);
        $client
            ->expects(self::once())
            ->method('sendRequest')
            ->willReturn($response);

        /** @var FbsService $svc */
        $svc = $this->createSvc($client, $this->createRequestFactory(), $this->createStreamFactory());
        $svc->get('');
    }

    public function testWithoutErrorDetails()
    {
        $content = <<<JSON
{
    "code":7,
    "message":"Invalid Api-Key, please contact support",
    "details":[]
}
JSON;

        $this->expectException(OzonSellerException::class);
        $this->expectExceptionMessage('Invalid Api-Key, please contact support');
        $this->expectExceptionCode(7);

        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects(self::once())
            ->method('getContents')
            ->willReturn($content);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(403);
        $response
            ->expects(self::once())
            ->method('getBody')
            ->willReturn($stream);

        $client = $this->createMock(ClientInterface::class);
        $client
            ->expects(self::once())
            ->method('sendRequest')
            ->willReturn($response);

        /** @var FbsService $svc */
        $svc = $this->createSvc($client, $this->createRequestFactory(), $this->createStreamFactory());
        $svc->get('');
    }

    public function testErrorWithoutCode(): void
    {
        $content = <<<JSON
{
    "error": {}
}
JSON;

        $this->expectException(OzonSellerException::class);
        $this->expectExceptionMessage('Ozon error');

        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects(self::once())
            ->method('getContents')
            ->willReturn($content);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects(self::once())
            ->method('getStatusCode')
            ->willReturn(403);
        $response
            ->expects(self::once())
            ->method('getBody')
            ->willReturn($stream);

        $client = $this->createMock(ClientInterface::class);
        $client
            ->expects(self::once())
            ->method('sendRequest')
            ->willReturn($response);

        /** @var FbsService $svc */
        $svc = $this->createSvc($client, $this->createRequestFactory(), $this->createStreamFactory());
        $svc->get('');
    }
}
