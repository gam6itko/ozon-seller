<?php

namespace Gam6itko\OzonSeller\Tests;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\TypeCaster
 * @group unit-test
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class TypeCasterTest extends TestCase
{
    /**
     * @param array $data
     * @param array $config
     * @param array $expected
     * @dataProvider dataCatArr
     */
    public function testCastArr(array $data, array $config, array $expected)
    {
        $result = \Gam6itko\OzonSeller\TypeCaster::castArr($data, $config);

        foreach ($result as $key => $val) {
            switch ($config[$key]) {
                case 'bool':
                    self::assertTrue(is_bool($val));
                    break;
                case 'str':
                case 'string':
                    self::assertTrue(is_string($val));
                    break;
                case 'int':
                case 'integer':
                    self::assertTrue(is_integer($val));
                    break;
                case 'float':
                    self::assertTrue(is_float($val));
                    break;
            }
        }

        self::assertJsonStringEqualsJsonString(json_encode($expected), json_encode($result));
    }

    public function dataCatArr()
    {
        return [
            [
                ['product_id' => 'must be 0', 'sku' => '1234', 'offer_id' => 1234141, 'bool_key' => 1, 'false_bool' => 0],
                ['product_id' => 'int', 'sku' => 'int', 'offer_id' => 'str', 'bool_key' => 'bool', 'false_bool' => 'bool'],
                ['product_id' => 0, 'sku' => 1234, 'offer_id' => '1234141', 'bool_key' => true, 'false_bool' => false],
            ],
        ];
    }
}
