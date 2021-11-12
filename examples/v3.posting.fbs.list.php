<?php declare(strict_types=1);

use Gam6itko\OzonSeller\Service\V3\Posting\FbsService;

$svcArgs = require_once __DIR__.'/bootstrap.php';

$svc = new FbsService(...$svcArgs);
$list = $svc->list();
var_dump($list);

$list = $svc->unfulfilledList([
    'filter' => [
        'delivering_date_from' => '2019-08-24T14:15:22Z',
        'delivering_date_to' => '2019-08-24T14:15:22Z'
    ]
]);
var_dump($list);
