<?php

$svcArgs = require_once __DIR__.'/bootstrap.php';

$svc = new \Gam6itko\OzonSeller\Service\V2\CategoryService(...$svcArgs);
$list = $svc->attribute(
    17027547,
    ['language' => \Gam6itko\OzonSeller\Enum\Language::EN]
);
var_dump($list);
