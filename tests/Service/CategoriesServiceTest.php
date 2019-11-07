<?php

namespace Gam6itko\OzonSeller\Tests\Service;

use Gam6itko\OzonSeller\Service\CategoriesService;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\CategoriesService
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class CategoriesServiceTest extends \PHPUnit\Framework\TestCase
{
    /** @var CategoriesService */
    private static $svc;

    public static function setUpBeforeClass()
    {
        self::$svc = new CategoriesService($_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']);
    }

    /**
     * @covers ::tree
     * @expectedException \Gam6itko\OzonSeller\Exception\NotFoundException
     */
    public function testTreeException()
    {
        $res = self::$svc->tree();
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
        self::assertCount(21, $res);
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
            [41777480, 'Куртка'],
            [17036379, 'Коврик туристический'],
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
