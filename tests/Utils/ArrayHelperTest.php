<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Utils;

use Gam6itko\OzonSeller\Utils\ArrayHelper;
use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{
    /**
     * @dataProvider dataPick
     */
    public function testPick(array $query, array $whitelist, array $expected): void
    {
        self::assertEquals(ArrayHelper::pick($query, $whitelist), $expected);
    }

    public function dataPick(): iterable
    {
        yield [
            [
                'foo' => 1,
                'bar' => 2,
                'baz' => 3,
            ],
            ['foo', 'hi', 'fellas'],
            [
                'foo' => 1,
            ],
        ];

        yield [
            [],
            ['foo', 'hi', 'fellas'],
            [],
        ];
    }
}
