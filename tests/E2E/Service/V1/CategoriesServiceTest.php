<?php

namespace Gam6itko\OzonSeller\Tests\E2E\Service\V1;

use Gam6itko\OzonSeller\Exception\NotFoundException;
use Gam6itko\OzonSeller\Service\V1\CategoriesService;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V1\CategoriesService
 * @group  v1
 * @group  e2e
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class CategoriesServiceTest extends TestCase
{
    /** @var CategoriesService */
    private static $svc;

    public static function setUpBeforeClass(): void
    {
        self::$svc = new CategoriesService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
    }

    protected function setUp(): void
    {
        sleep(1); //fix 429 Too Many Requests
    }

    /**
     * @covers ::tree
     */
    public function testTreeException()
    {
        $this->expectException(NotFoundException::class);
        $res = self::$svc->tree(1917);
        self::assertNotEmpty($res);
    }

    /**
     * @covers ::tree
     */
    public function testTreeRoot()
    {
        $res = self::$svc->tree();
        self::assertNotEmpty($res);
        self::assertIsArray($res);
        self::assertArrayHasKey('category_id', $res[0]);
        self::assertArrayHasKey('title', $res[0]);
        self::assertArrayHasKey('children', $res[0]);
        self::assertCount(24, $res);
    }

    /**
     * @covers ::attributes
     * @dataProvider dataTree
     */
    public function testTree(int $id, string $title)
    {
        $res = self::$svc->tree($id);
        self::assertNotEmpty($res);
        self::assertCount(1, $res);
        $cat = $res[0];
        self::assertEquals($title, $cat['title']);
    }

    public function dataTree()
    {
        return [
            [17027492, 'Канцелярия'],
            [72078193, 'Аксессуар для информационного держателя'],
        ];
    }

    /**
     * @covers ::attributes
     * @dataProvider dataAttributes
     */
    public function testAttributes(int $id)
    {
        $res = self::$svc->attributes($id);
        self::assertNotEmpty($res);
    }

    public function dataAttributes()
    {
        return [
            [17029835],
        ];
    }
}
