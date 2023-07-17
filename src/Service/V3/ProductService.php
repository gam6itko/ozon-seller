<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V3;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

/**
 * @psalm-type TInfoStocksRequestFilter = array{
 *     offer_id: list<string>,
 *     product_id: list<string>,
 *     visibility: string
 * }
 * @psalm-type TInfoStocksResponseStocks = array{
 *      present: int,
 *      reserver: int,
 *      type: string
 * }
 * @psalm-type TInfoStocksResponseItem = array{
 *      offer_id: string,
 *      product_id: int,
 *      stocks: list<TInfoStocksResponseStocks>
 * }
 * @psalm-type TInfoStocksResponse = array{
 *      items: list<TInfoStocksResponseItem>,
 *      last_id: string,
 *      limit: int
 * }
 */
class ProductService extends AbstractService
{
    private $path = '/v3/product';

    public function importStocks(array $filter, ?string $lastId = '', int $limit = 100)
    {
        assert($limit > 0 && $limit <= 1000);

        $body = [
            'filter'  => ArrayHelper::pick($filter, ['offer_id', 'product_id', 'visibility']),
            'last_id' => $lastId ?? '',
            'limit'   => $limit,
        ];

        return $this->request('POST', "{$this->path}s/stocks", $body);
    }

    public function infoAttributes(array $filter, ?string $lastId = '', int $limit = 100, string $sortBy = 'product_id', string $sortDir = SortDirection::DESC)
    {
        $body = [
            'filter'   => ArrayHelper::pick($filter, ['offer_id', 'product_id', 'visibility']),
            'last_id'  => $lastId ?? '',
            'limit'    => $limit,
            'sort_by'  => $sortBy,
            'sort_dir' => $sortDir,
        ];

        return $this->request('POST', "{$this->path}s/info/attributes", $body);
    }

    /**
     * @param TInfoStocksRequestFilter $filter
     *
     * @return TInfoStocksResponse
     */
    public function infoStocks(array $filter, ?string $lastId = '', int $limit = 100): array
    {
        $body = [
            'filter'  => ArrayHelper::pick($filter, ['offer_id', 'product_id', 'visibility']),
            'last_id' => $lastId ?? '',
            'limit'   => $limit,
        ];

        return $this->request('POST', "{$this->path}/info/stocks", $body);
    }
}
