<?php

namespace Gam6itko\OzonSeller\Tests\Service\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Enum\Status;
use Gam6itko\OzonSeller\Service\CategoriesService;
use Gam6itko\OzonSeller\Service\Posting\FbsService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\Posting\FbsService
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 * @group v2
 */
class FbsServiceTest extends TestCase
{
    /** @var CategoriesService */
    private static $svc;

    public static function setUpBeforeClass()
    {
        self::$svc = new FbsService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
    }

    protected function setUp()
    {
        sleep(1); //fix 429 Too Many Requests
    }

    /**
     * @covers ::list
     */
    public function testList()
    {
        self::$svc->list(SortDirection::ASC, 0, 10, ['since' => new \DateTime('2019-01-01'), 'to' => new \DateTime('2020-01-01')]);
        self::assertTrue(true);
    }

    /**
     * @covers ::get
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testGet()
    {
        self::$svc->get('123456790');
    }

    /**
     * @covers ::unfulfilledList
     */
    public function testUnfulfilledList()
    {
        self::$svc->unfulfilledList(Status::AWAITING_PACKAGING);
        self::assertTrue(true);
    }

    /**
     * @covers ::unfulfilledList
     * @expectedException \LogicException
     * @expectedExceptionMessage Incorrect status `sending out of space`
     */
    public function testUnfulfilledListFail()
    {
        self::$svc->unfulfilledList('sending out of space');
    }

    /**
     * @covers ::ship
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testShip()
    {
        self::$svc->ship([
            [
                'items' => [
                    [
                        'quantity' => 3,
                        'sku'      => 123065,
                    ],
                ],
            ],
        ], '13076543-0001-1');
        self::assertTrue(true);
    }

    /**
     * @covers ::actCreate
     * @expectedException \Gam6itko\OzonSeller\Exception\PostingNotFoundException
     */
    public function testActCreate()
    {
        $res = self::$svc->actCreate();
        self:self::assertNotEmpty($res);
    }

    /**
     * @covers ::actCheckStatus
     * @expectedException \Gam6itko\OzonSeller\Exception\PostingNotFoundException
     */
    public function testActCheckStatus()
    {
        self::$svc->actCheckStatus(123);
    }

    /**
     * @covers ::packageLabel
     * @expectedException \Gam6itko\OzonSeller\Exception\BadRequestException
     */
    public function testPackageLabel()
    {
        self::$svc->packageLabel('13770987-0051-1');
    }

    /**
     * @covers ::arbitration
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testArbitration()
    {
        self::$svc->arbitration('13070987-0051-1');
    }
}
