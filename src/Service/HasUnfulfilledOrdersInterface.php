<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service;

interface HasUnfulfilledOrdersInterface
{
    public function unfulfilledList(array $requestData = []): array;
}
