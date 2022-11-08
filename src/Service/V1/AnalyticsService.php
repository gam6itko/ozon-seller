<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1;

use Gam6itko\OzonSeller\Service\AbstractService;

class AnalyticsService extends AbstractService
{
    private $path = '/v1/analytics';

    /**
     * Specify the period and metrics that are required.
     *
     * @see https://docs.ozon.ru/api/seller/en/#operation/AnalyticsAPI_AnalyticsGetData
     */
    public function data(
        \DateTimeInterface $dateFrom,
        \DateTimeInterface $dateTo,
        array $dimension,
        array $metrics,
        int $offset = 0,
        int $limit = 10,
        array $filters = [],
        array $sort = []
    ): array {
        $body = [
            'date_from' => $dateFrom->format('Y-m-d'),
            'date_to' => $dateTo->format('Y-m-d'),
            'dimension' => $dimension,
            'metrics' => $metrics,
            'offset' => $offset,
            'limit' => $limit,
            'filters' => $filters,
            'sort' => $sort,
        ];

        return $this->request('POST', "{$this->path}/data", $body);
    }

    /**
     * Report on stocks and products movement at Ozon warehouses..
     *
     * @see https://docs.ozon.ru/api/seller/en/#operation/AnalyticsAPI_AnalyticsGetStockOnWarehouses
     */
    public function stockOnWarehouses(int $offset = 0, int $limit = 10): array
    {
        $body = [
            'offset' => $offset,
            'limit' => $limit,
        ];

        return $this->request(
            'POST',
            "{$this->path}/stock_on_warehouses",
            $body
        );
    }

    /**
     * Method for getting a turnover report (FBO) by category for 15 days.
     *
     * @see https://docs.ozon.ru/api/seller/en/#operation/AnalyticsAPI_AnalyticsItemTurnoverDataV3
     */
    public function itemTurnover(\DateTimeInterface $dateFrom): array
    {
        $body = [
            'date_from' => $dateFrom->format('Y-m-d'),
        ];

        return $this->request('POST', "{$this->path}/item_turnover", $body);
    }
}
