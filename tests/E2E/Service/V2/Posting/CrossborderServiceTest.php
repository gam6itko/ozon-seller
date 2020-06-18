<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\E2E\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Enum\Status;
use Gam6itko\OzonSeller\Exception\BadRequestException;
use Gam6itko\OzonSeller\Exception\NotFoundException;
use Gam6itko\OzonSeller\Service\V2\Posting\CrossborderService;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V2\Posting\CrossborderService
 * @group  v2
 * @group  e2e
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class CrossborderServiceTest extends TestCase
{
    /** @var CrossborderService */
    private static $svc;

    public static function setUpBeforeClass(): void
    {
        $config = [$_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']];
        $adapter = new GuzzleAdapter(new GuzzleClient());
        self::$svc = new CrossborderService($config, $adapter);
    }

    protected function setUp(): void
    {
        sleep(1); //fix 429 Too Many Requests
    }

    /**
     * @covers ::list
     */
    public function testList()
    {
        self::$svc->list(SortDirection::ASC, 0, 10, [
            'since'  => new \DateTime('2019-01-01'),
            'to'     => new \DateTime('2020-01-01'),
            'status' => Status::AWAITING_APPROVE,
        ]);
        self::assertTrue(true);
    }

    /**
     * @covers ::get
     */
    public function testGet()
    {
        $this->expectException(NotFoundException::class);
        self::$svc->get('123456790');
    }

    /**
     * @covers ::unfulfilledList
     */
    public function testUnfulfilledList()
    {
        self::$svc->unfulfilledList(Status::AWAITING_APPROVE);
        self::assertTrue(true);
    }

    /**
     * @covers ::approve
     */
    public function testApprove()
    {
        $this->expectException(NotFoundException::class);
        self::$svc->approve('123456');
        self::assertTrue(true);
    }

    /**
     * @covers ::cancel
     */
    public function testCancel()
    {
        $this->expectException(BadRequestException::class);
        self::$svc->cancel('39268230-0002-3', '149123456', 349, 'Cancel reason');
        self::assertTrue(true);
    }

    /**
     * @covers ::cancelReasons
     */
    public function testCancelReasons()
    {
        $result = self::$svc->cancelReasons();
        self::assertNotNull($result);
        self::assertArrayHasKey('id', $result[0]);
        self::assertArrayHasKey('title', $result[0]);
        self::assertArrayHasKey('type_id', $result[0]);
    }

    /**
     * @covers ::ship
     */
    public function testShip()
    {
        $this->expectException(NotFoundException::class);
        self::$svc->ship('39268230-0002-3', 'AB123456CD', 15109877837000, [
            [
                'quantity' => 2,
                'sku'      => 100056,
            ],
        ]);
        self::assertTrue(true);
    }
}
