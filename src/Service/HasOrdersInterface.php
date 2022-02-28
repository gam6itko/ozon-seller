<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service;

interface HasOrdersInterface
{
    public function list(array $requestData): array;
}
