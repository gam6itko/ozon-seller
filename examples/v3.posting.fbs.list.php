<?php

use Gam6itko\OzonSeller\Service\V3\Posting\FbsService;

$svcArgs = require_once __DIR__.'/bootstrap.php';

$svc = new FbsService(...$svcArgs);
$list = $svc->list();
var_dump($list);
