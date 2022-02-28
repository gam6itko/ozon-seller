<?php

declare(strict_types=1);

return [
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
    'image_group_id' => ['type' => 'str', 'requiredCreate' => false, 'requiredUpdate' => false],
    'images'         => ['type' => 'array', 'requiredCreate' => true, 'requiredUpdate' => false],
    'images360'      => ['type' => 'array', 'requiredCreate' => false, 'requiredUpdate' => false],
    'pdf_list'       => ['type' => 'array', 'requiredCreate' => false, 'requiredUpdate' => false],
    'height'         => ['type' => 'int', 'requiredCreate' => true, 'requiredUpdate' => false],
    'depth'          => ['type' => 'int', 'requiredCreate' => true, 'requiredUpdate' => false],
    'width'          => ['type' => 'int', 'requiredCreate' => true, 'requiredUpdate' => false],
    'dimension_unit' => [
        'type'           => 'str',
        'requiredCreate' => true,
        'requiredUpdate' => false,
        'options'        => ['mm', 'cm', 'in'],
    ],
    'weight'         => ['type' => 'int', 'requiredCreate' => true, 'requiredUpdate' => false],
    'weight_unit'    => [
        'type'           => 'str',
        'requiredCreate' => true,
        'requiredUpdate' => false,
        'options'        => ['g', 'kg', 'lb'],
    ],
];
