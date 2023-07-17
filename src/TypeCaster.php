<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class TypeCaster
{
    /**
     * @param array $data   Array with cast types
     * @param array $config ['float_key' => 'float', 'str_key' => 'string', 'int_key' => 'int']
     *
     * @return array Modified data
     */
    public static function castArr(array $data, array $config)
    {
        foreach ($data as $key => &$val) {
            if (array_key_exists($key, $config) && null !== $val) {
                $val = self::cast($val, $config[$key]);
            }
        }

        return $data;
    }

    public static function cast($val, string $type)
    {
        switch (self::normalizeType($type)) {
            case 'boolean':
                return (bool) $val;
            case 'string':
                return (string) $val;
            case 'integer':
                return (int) $val;
            case 'float':
                return (float) $val;
            case 'array':
                return (array) $val;
            case 'arrayOfInt':
                return array_map(function ($v): int {
                    return (int) $v;
                }, (array) $val);
            case 'arrayOfString':
                return array_map(function ($v): string {
                    return (string) $v;
                }, (array) $val);
            default:
                assert(false, 'Unsupported typecast '.$type);

                return $val;
        }
    }

    public static function normalizeType(string $type): string
    {
        switch ($type) {
            case 'arr':
            case 'array':
                return 'array';
            case 'arrOfInt':
            case 'arrayOfInt':
                return 'arrayOfInt';
            case 'arrOfStr':
            case 'arrayOfString':
                return 'arrayOfString';
            case 'bool':
            case 'boolean':
                return 'boolean';
            case 'str':
            case 'string':
                return 'string';
            case 'int':
            case 'integer':
                return 'integer';
            case 'float':
            case 'double':
                return 'float';
            default:
                throw new \LogicException("Unsupported type: $type");
        }
    }
}
