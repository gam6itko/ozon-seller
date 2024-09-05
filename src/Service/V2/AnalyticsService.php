<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V2;

use Gam6itko\OzonSeller\Service\AbstractService;

class AnalyticsService extends AbstractService
{
    private $path = '/v2/analytics';

    /**
     * Method for getting a report on leftover stocks and products movement at Ozon warehouses.
     *
     * @see https://docs.ozon.ru/api/seller/en/#operation/AnalyticsAPI_AnalyticsGetStockOnWarehousesV2
     */
    public function stockOnWarehouses(int $offset = 0, int $limit = 10, $warehouse_type = "ALL"): array
    {
        $body = [
            'offset' => $offset,
            'limit'  => $limit,
            'warehouse_type' => $warehouse_type
        ];

        return $this->request(
            'POST',
            "{$this->path}/stock_on_warehouses",
            $body
        );
    }

}
