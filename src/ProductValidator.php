<?php
namespace Gam6itko\OzonSeller;

class ProductValidator
{
    public const PROPERTIES = [
        'barcode'        => ['type' => 'str', 'required' => false],
        'description'    => ['type' => 'str', 'required' => true],
        'category_id'    => ['type' => 'int', 'required' => true],
        'name'           => ['type' => 'str', 'required' => true],
        'offer_id'       => ['type' => 'str', 'required' => true],
        'price'          => ['type' => 'str', 'required' => true],
        'old_price'      => ['type' => 'str', 'required' => false],
        'premium_price'  => ['type' => 'str', 'required' => false],
        'vat'            => ['type' => 'str', 'required' => true],
        'vendor'         => ['type' => 'str', 'required' => false],
        'vendor_code'    => ['type' => 'str', 'required' => false],
        'attributes'     => ['type' => 'array', 'required' => false],
        'images'         => ['type' => 'array', 'required' => true],
        'height'         => ['type' => 'int', 'required' => true],
        'depth'          => ['type' => 'int', 'required' => true],
        'width'          => ['type' => 'int', 'required' => true],
        'dimension_unit' => ['type' => 'str', 'required' => true, 'options' => ['mm', 'cm', 'in']],
        'weight'         => ['type' => 'int', 'required' => true],
        'weight_unit'    => ['type' => 'str', 'required' => true, 'options' => ['g', 'kg', 'lb']],
    ];

    /** @var array */
    private $requiredKeys;

    /** @var array */
    private $optProps;

    /** @var array */
    private $typeCast;

    /**
     * ProductValidator constructor.
     */
    public function __construct()
    {
        $this->requiredKeys = array_keys(array_filter(array_map(function ($arr) {
            return $arr['required'] ?? false;
        }, self::PROPERTIES)));

        $this->optProps = array_filter(array_map(function ($arr) {
            return $arr['options'] ?? null;
        }, self::PROPERTIES));

        $this->typeCast = array_map(function ($arr) {
            return $arr['type'];
        }, self::PROPERTIES);
    }

    public function validateItem(array $item)
    {
        foreach ($this->requiredKeys as $key) {
            if (!array_key_exists($key, $item)) {
                throw new \LogicException("Required property not defined: $key");
            }
        }

        foreach ($this->optProps as $key => $options) {
            if (isset($item[$key]) && !in_array($item[$key], $options)) {
                throw new \LogicException("Incorrect property value '{$item[$key]}' for `$key` key");
            }
        }

        return TypeCaster::castArr($item, $this->typeCast, false);
    }
}