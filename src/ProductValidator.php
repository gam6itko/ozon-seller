<?php
namespace Gam6itko\OzonSeller;

use Gam6itko\OzonSeller\Exception\ProductValidatorException;

class ProductValidator
{
    private const MAX_IMAGES_COUNT = 10;

    public const PROPERTIES = [
        'product_id'     => ['type' => 'int', 'requiredCreate' => false, 'requiredUpdate' => true],
        'barcode'        => ['type' => 'str', 'requiredCreate' => false, 'requiredUpdate' => false],
        'description'    => ['type' => 'str', 'requiredCreate' => true, 'requiredUpdate' => false],
        'category_id'    => ['type' => 'int', 'requiredCreate' => true, 'requiredUpdate' => false],
        'name'           => ['type' => 'str', 'requiredCreate' => true, 'requiredUpdate' => false],
        'offer_id'       => ['type' => 'str', 'requiredCreate' => true, 'requiredUpdate' => false],
        'price'          => ['type' => 'str', 'requiredCreate' => true, 'requiredUpdate' => false],
        'old_price'      => ['type' => 'str', 'requiredCreate' => false, 'requiredUpdate' => false],
        'premium_price'  => ['type' => 'str', 'requiredCreate' => false, 'requiredUpdate' => false],
        'vat'            => ['type' => 'str', 'requiredCreate' => true, 'requiredUpdate' => false],
        'vendor'         => ['type' => 'str', 'requiredCreate' => false, 'requiredUpdate' => false],
        'vendor_code'    => ['type' => 'str', 'requiredCreate' => false, 'requiredUpdate' => false],
        'attributes'     => ['type' => 'array', 'requiredCreate' => false, 'requiredUpdate' => false],
        'images'         => ['type' => 'array', 'requiredCreate' => true, 'requiredUpdate' => false],
        'height'         => ['type' => 'int', 'requiredCreate' => true, 'requiredUpdate' => false],
        'depth'          => ['type' => 'int', 'requiredCreate' => true, 'requiredUpdate' => false],
        'width'          => ['type' => 'int', 'requiredCreate' => true, 'requiredUpdate' => false],
        'dimension_unit' => ['type' => 'str', 'requiredCreate' => true, 'requiredUpdate' => false, 'options' => ['mm', 'cm', 'in']],
        'weight'         => ['type' => 'int', 'requiredCreate' => true, 'requiredUpdate' => false],
        'weight_unit'    => ['type' => 'str', 'requiredCreate' => true, 'requiredUpdate' => false, 'options' => ['g', 'kg', 'lb']],
    ];

    /** @var array */
    private $requiredKeys;

    /** @var array */
    private $optProps;

    /** @var array */
    private $typeCast;

    /**
     * ProductValidator constructor.
     * @param string $mode
     */
    public function __construct(string $mode = 'create')
    {
        if (!in_array($mode, ['create', 'update'])) {
            throw new \LogicException('Mode must be [create, update]');
        }

        $this->requiredKeys = array_keys(array_filter(array_map(function ($arr) use ($mode) {
            return $arr['required' . ucfirst($mode)] ?? false;
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
                throw new ProductValidatorException("Required property not defined: $key", $item);
            }
        }

        foreach ($this->optProps as $key => $options) {
            if (isset($item[$key]) && !in_array($item[$key], $options)) {
                throw new ProductValidatorException("Incorrect property value '{$item[$key]}' for `$key` key");
            }
        }

        if (isset($item['images']) && count($item['images']) > self::MAX_IMAGES_COUNT) {
            array_splice($item['images'], 0, self::MAX_IMAGES_COUNT);
        }

        return TypeCaster::castArr($item, $this->typeCast, false);
    }
}