<?php

namespace Gam6itko\OzonSeller\Tests\Service;

use Gam6itko\OzonSeller\Enum\DeliverySchema;
use Gam6itko\OzonSeller\Service\OrderService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\OrderService
 * @group  v1
 *
 * @deprecated
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class OrderServiceTest extends TestCase
{
    /** @var OrderService */
    private static $svc;

    public static function setUpBeforeClass()
    {
        self::$svc = new OrderService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
    }

    protected function setUp()
    {
        sleep(1); //fix 429 Too Many Requests
    }

    public function testListCrossborder()
    {
        $response = self::$svc->list(new \DateTime('2018-01-01'), new \DateTime('2018-12-31'), DeliverySchema::CROSSBORDER);
        self::assertNotEmpty($response);
    }

    public function testListFbo()
    {
        $response = self::$svc->list(new \DateTime('2018-01-01'), new \DateTime('2018-12-31'), DeliverySchema::FBO);
        self::assertNotEmpty($response);
    }

    public function testListFbs()
    {
        $response = self::$svc->list(new \DateTime('2018-01-01'), new \DateTime('2018-12-31'), DeliverySchema::FBS);
        self::assertNotEmpty($response);
    }

    /**
     * @covers \OrderService::itemsCancelReasons
     */
    public function testItemsCancelReasons()
    {
        $result = self::$svc->itemsCancelReasons();
        self::assertNotEmpty($result);
    }

    public function testShippingProviders()
    {
        $result = self::$svc->shippingProviders();
        self::assertTrue(true);
    }
}
