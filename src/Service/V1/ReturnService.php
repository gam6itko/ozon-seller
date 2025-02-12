<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

/**
 * @psalm-type TReturnListRequestFilter = array{
 *      logistic_return_date?: object<string>,
 *      storage_tariffication_start_date?: object<string>,
 *      visual_status_change_moment?: object<string>,
 *      order_id?: int,
 *      posting_numbers?: list<string>,
 *      product_name?: string,
 *      offer_id?: string,
 *      visual_status_name?: string,
 *      warehouse_id?: int,
 *      barcode?: string,
 *      return_schema?: string
 * }
 *
 * @psalm-type TReturnListRequestLimit = int
 *
 * @psalm-type TReturnListRequestLastId = int
 *
 * @psalm-type TReturnListRequestResponse = array{
 *       returns: array<object>,
 *       has_next: bool
 * }
 */
class ReturnService extends AbstractService
{
    private $path = '/v1/returns';

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/returnsList
     *
     * @param TReturnListRequestFilter $filter
     * @param TReturnListRequestLastId $lastId
     * @param TReturnListRequestLimit $limit
     *
     * @return TReturnListRequestResponse
     */
    public function list(array $filter, int $lastId = 0, int $limit = 100): array
    {
        assert($limit > 0 && $limit <= 500);

        $body = [
            'filter' => ArrayHelper::pick($filter, [
                'logistic_return_date', 'storage_tariffication_start_date', 'visual_status_change_moment',
                'order_id', 'posting_numbers', 'product_name', 'offer_id', 'visual_status_name', 'warehouse_id',
                'barcode', 'return_schema'
            ]),
            'last_id' => $lastId,
            'limit' => $limit,
        ];

        return $this->request('POST', "{$this->path}/list", $body);
    }
}
