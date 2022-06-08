<?php

declare(strict_types=1);

use Gam6itko\OzonSeller\Service\V3\Posting\FbsService;

$svcArgs = require_once __DIR__.'/bootstrap.php';

$svc = new FbsService(...$svcArgs);

$products = [
    [
        'exemplar_info'  => [
            [
                'is_gtd_absent' => true
            ]
        ],
        'quantity'       => 1,
        'product_id'     => 2222222222,
    ]
];

$packages = [
    [
        'products' => $products
    ]
];

$response = $svc->ship($packages, '111111111-2222-3', ['additional_data' => true]);
var_dump($response);
