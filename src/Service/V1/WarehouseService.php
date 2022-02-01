<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1;

use Gam6itko\OzonSeller\Service\AbstractService;

class WarehouseService extends AbstractService
{
    private $path = '/v1/warehouse';

    public function list(): array
    {
        return $this->request('POST', "{$this->path}/list");
    }
}
