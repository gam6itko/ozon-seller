<?php

declare(strict_types=1);

return [
    'offer_id' => [
        'type' => 'string',
        'requiredCreate' => true,
        'requiredUpdate' => true,
    ],

    'description_category_id' => [
        'type' => 'integer',
        'requiredCreate' => true,
        'requiredUpdate' => false,
    ],

    'type_id' => [
        'type' => 'integer',
        'requiredCreate' => false,
        'requiredUpdate' => false,
    ],

    'name' => [
        'type' => 'string',
        'requiredCreate' => true,
        'requiredUpdate' => false,
    ],

    'description' => [
        'type' => 'string',
        'requiredCreate' => false,
        'requiredUpdate' => false,
    ],

    'attributes' => [
        'type' => 'array',
        'requiredCreate' => true,
        'requiredUpdate' => true,
    ],

    'complex_attributes' => [
        'type' => 'array',
        'requiredCreate' => false,
        'requiredUpdate' => false,
    ],

    'barcode' => [
        'type' => 'string',
        'requiredCreate' => false,
        'requiredUpdate' => false,
    ],

    'width' => [
        'type' => 'integer',
        'requiredCreate' => true,
        'requiredUpdate' => false,
    ],

    'height' => [
        'type' => 'integer',
        'requiredCreate' => true,
        'requiredUpdate' => false,
    ],

    'depth' => [
        'type' => 'integer',
        'requiredCreate' => true,
        'requiredUpdate' => false,
    ],

    'dimension_unit' => [
        'type' => 'string',
        'requiredCreate' => true,
        'requiredUpdate' => false,
        'options' => ['mm', 'cm', 'in'],
    ],

    'weight' => [
        'type' => 'integer',
        'requiredCreate' => true,
        'requiredUpdate' => false,
    ],

    'weight_unit' => [
        'type' => 'string',
        'requiredCreate' => true,
        'requiredUpdate' => false,
        'options' => ['g', 'kg', 'lb'],
    ],

    'primary_image' => [
        'type' => 'string',
        'requiredCreate' => false,
        'requiredUpdate' => false,
    ],

    'images' => [
        'type' => 'arrayOfString',
        'requiredCreate' => true,
        'requiredUpdate' => false,
    ],

    'images360' => [
        'type' => 'arrayOfString',
        'requiredCreate' => false,
        'requiredUpdate' => false,
    ],

    'color_image' => [
        'type' => 'string',
        'requiredCreate' => false,
        'requiredUpdate' => false,
    ],

    'price' => [
        'type' => 'string',
        'requiredCreate' => true,
        'requiredUpdate' => false,
    ],

    'old_price' => [
        'type' => 'string',
        'requiredCreate' => false,
        'requiredUpdate' => false,
    ],

    'vat' => [
        'type' => 'string',
        'requiredCreate' => true,
        'requiredUpdate' => false,
        'options' => ['0', '0.1', '0.2'],
    ],

    'currency_code' => [
        'type' => 'string',
        'requiredCreate' => true,
        'requiredUpdate' => false,
        'options' => ['RUB', 'CNY', 'KZT'],
    ],

    'pdf_list' => [
        'type' => 'arrayOfString',
        'requiredCreate' => false,
        'requiredUpdate' => false,
    ],
];
