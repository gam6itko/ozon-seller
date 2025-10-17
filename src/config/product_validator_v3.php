<?php

declare(strict_types=1);

return [
    'offer_id' => [
        'type' => 'string',
        'requiredCreate' => true,
    ],

    'description_category_id' => [
        'type' => 'integer',
        'requiredCreate' => true,
    ],

    'type_id' => [
        'type' => 'integer',
        'requiredCreate' => false,
    ],

    'name' => [
        'type' => 'string',
        'requiredCreate' => true,
    ],

    'description' => [
        'type' => 'string',
        'requiredCreate' => false,
    ],

    'attributes' => [
        'type' => 'array',
        'requiredCreate' => true,
    ],

    'complex_attributes' => [
        'type' => 'array',
        'requiredCreate' => false,
    ],

    'barcode' => [
        'type' => 'string',
        'requiredCreate' => false,
    ],

    'width' => [
        'type' => 'integer',
        'requiredCreate' => true,
    ],

    'height' => [
        'type' => 'integer',
        'requiredCreate' => true,
    ],

    'depth' => [
        'type' => 'integer',
        'requiredCreate' => true,
    ],

    'dimension_unit' => [
        'type' => 'string',
        'requiredCreate' => true,
        'options' => ['mm', 'cm', 'in'],
    ],

    'weight' => [
        'type' => 'integer',
        'requiredCreate' => true,
    ],

    'weight_unit' => [
        'type' => 'string',
        'requiredCreate' => true,
        'options' => ['g', 'kg', 'lb'],
    ],

    'primary_image' => [
        'type' => 'string',
        'requiredCreate' => false,
    ],

    'images' => [
        'type' => 'arrayOfString',
        'requiredCreate' => true,
    ],

    'images360' => [
        'type' => 'arrayOfString',
        'requiredCreate' => false,
    ],

    'color_image' => [
        'type' => 'string',
        'requiredCreate' => false,
    ],

    'price' => [
        'type' => 'string',
        'requiredCreate' => true,
    ],

    'old_price' => [
        'type' => 'string',
        'requiredCreate' => false,
    ],

    'vat' => [
        'type' => 'string',
        'requiredCreate' => false,
        'options' => ['0', '0.05', '0.07', '0.1', '0.2'],
    ],

    'currency_code' => [
        'type' => 'string',
        'requiredCreate' => true,
        'options' => ['RUB', 'BYN', 'KZT', 'EUR', 'USD', 'CNY'],
    ],

    'pdf_list' => [
        'type' => 'arrayOfString',
        'requiredCreate' => false,
    ],

    'promotions' => [
        'type' => 'array',
        'requiredCreate' => false,
    ],
];
