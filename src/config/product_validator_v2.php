<?php declare(strict_types=1);

return [
    'name' => ['type' => 'string', 'requiredCreate' => false],

    'offer_id' => ['type' => 'string', 'requiredCreate' => true],

    'attributes'         => [
        'type'           => 'array', //todo type attributes array
        'requiredCreate' => true,
    ],
    'complex_attributes' => ['type' => 'array', 'requiredCreate' => false],

    'barcode'     => ['type' => 'str', 'requiredCreate' => false],
    'category_id' => ['type' => 'int', 'requiredCreate' => true],

    'width'          => ['type' => 'int', 'requiredCreate' => true],
    'height'         => ['type' => 'int', 'requiredCreate' => true],
    'depth'          => ['type' => 'int', 'requiredCreate' => true],
    'dimension_unit' => [
        'type'           => 'str',
        'requiredCreate' => true,
        'options'        => ['mm', 'cm', 'in'],
    ],

    'weight'      => ['type' => 'int', 'requiredCreate' => true],
    'weight_unit' => [
        'type'           => 'str',
        'requiredCreate' => true,
        'options'        => ['g', 'kg', 'lb'],
    ],

    'image_group_id' => ['type' => 'str', 'requiredCreate' => false],
    'images'         => ['type' => 'array', 'requiredCreate' => true],
    'images360'      => [
        'type'           => 'array', //todo type image360
        'requiredCreate' => false,
    ],

    'pdf_list' => [
        'type'           => 'array', //todo type pdf_list
        'requiredCreate' => false,
    ],

    'old_price'     => ['type' => 'str', 'requiredCreate' => false],
    'price'         => ['type' => 'str', 'requiredCreate' => true],
    'premium_price' => ['type' => 'str', 'requiredCreate' => false],
    'vat'           => ['type' => 'str', 'requiredCreate' => true],
];
