<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\E2E;

use Gam6itko\OzonSeller\Exception\BadRequestException;
use Gam6itko\OzonSeller\Service\V1\ProductService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Psr18Client;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 *
 * @group  e2e
 */
class SymfonyTest extends TestCase
{
    public function test(): void
    {
        $config = [$_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']];
        $client = new Psr18Client();
        $svc = new ProductService($config, $client);
        $products = $svc->list(
            [],
            ['page' => 1, 'page_size' => 1]
        );
        self::assertNotEmpty($products);
        self::assertArrayHasKey('total', $products);
        self::assertArrayHasKey('items', $products);
    }

    public function testThrows()
    {
        $this->expectException(BadRequestException::class);

        $config = [$_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']];
        $client = new Psr18Client();
        $svc = new ProductService($config, $client);
        $status = $svc->delete(123);
        self::assertNotEmpty($status);
    }
}
