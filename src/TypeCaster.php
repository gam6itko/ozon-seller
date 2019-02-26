<?php
namespace Gam6itko\OzonSeller;

class TypeCaster
{
    /**
     * @param array $data Array with cast types
     * @param array $config ['float_key' => 'float', 'str_key' => 'string', 'int_key' => 'int']
     * @return array Modified data
     */
    public static function castArr(array $data, array $config)
    {
        foreach ($data as $key => &$val) {
            if (array_key_exists($key, $config)) {
                switch ($config[$key]) {
                    case 'bool':
                        $val = (bool)$val;
                        break;
                    case 'str':
                    case 'string':
                        $val = (string)$val;
                        break;
                    case 'int':
                    case 'integer':
                        $val = (int)$val;
                        break;
                    case 'float':
                        $val = (float)$val;
                        break;
                    default:
                        throw new \LogicException("Unsupported type: {$config[$key]}");
                }
            }
        }

        return $data;
    }
}