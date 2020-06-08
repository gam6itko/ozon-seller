<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\E2E\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Enum\Status;
use Gam6itko\OzonSeller\Exception\NotFoundException;
use Gam6itko\OzonSeller\Exception\NotFoundInSortingCenterException;
use Gam6itko\OzonSeller\Service\V1\CategoriesService;
use Gam6itko\OzonSeller\Service\V2\Posting\FbsService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V2\Posting\FbsService
 * @group  v2
 * @group  e2e
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class FbsServiceTest extends TestCase
{
    /** @var CategoriesService */
    private static $svc;

    public static function setUpBeforeClass(): void
    {
        self::$svc = new FbsService((int) $_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
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
        self::$svc->list(SortDirection::ASC, 0, 10, ['since' => new \DateTime('2018-01-01'), 'to' => new \DateTime('2020-01-01')]);
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
        self::$svc->unfulfilledList(Status::AWAITING_PACKAGING);
        self::assertTrue(true);
    }

    /**
     * @covers ::unfulfilledList
     */
    public function testUnfulfilledListFail()
    {
        $this->expectExceptionMessage('Incorrect status `sending out of space`');
        $this->expectException(\LogicException::class);
        self::$svc->unfulfilledList('sending out of space');
    }

    /**
     * @covers ::ship
     */
    public function testShip()
    {
        $this->expectException(NotFoundException::class);
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
     */
    public function testActCreate()
    {
        $this->expectException(NotFoundInSortingCenterException::class);
        $res = self::$svc->actCreate();
        self::assertNotEmpty($res);
    }

    /**
     * @covers ::actCheckStatus
     */
    public function testActCheckStatus()
    {
        $this->expectException(NotFoundException::class);
        self::$svc->actCheckStatus(123);
    }

    /**
     * @covers ::actGetPdf
     */
    public function testActGetPdf()
    {
        $this->expectException(NotFoundException::class);
        self::$svc->actGetPdf(15684442104000);
    }

    /**
     * @covers ::packageLabel
     */
    public function testPackageLabel()
    {
        $fileData = self::$svc->packageLabel('25849584-0029-1');
        self::assertNotEmpty($fileData);
//        file_put_contents('package-label.pdf', $fileData);
    }

    /**
     * @covers ::arbitration
     */
    public function testArbitration()
    {
        $this->expectException(NotFoundException::class);
        self::$svc->arbitration('13070987-0051-1');
    }
}
