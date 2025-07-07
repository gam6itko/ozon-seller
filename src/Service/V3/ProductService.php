<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V3;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\ProductValidator;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\TypeCaster;
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
 * @psalm-type TProductListRequestFilter = array{
 *      offer_id?: list<string>,
 *      product_id?: list<string>,
 *      visibility?: string
 *  }
 * @psalm-type TProductListResponseItemQuant = array{
 *      quant_code: string,
 *      quant_size: int,
 *  }
 * @psalm-type TProductListResponseItem = array{
 *      archived: bool,
 *      has_fbo_stocks: bool,
 *      has_fbs_stocks: bool,
 *      is_discounted: bool,
 *      offer_id: string,
 *      product_id: int,
 *      quants: list<TProductListResponseItemQuant>
 *  }
 * @psalm-type TProductListResponse = array{
 *      items: list<TProductListResponseItem>,
 *      last_id: string,
 *      total: int
 *  }
 * @psalm-type TProductInfoListResponse = array{
 *      items: list<array<string, mixed>>,
 * }
 * @psalm-type TProductInfoListRequest = array{
 *      offer_id?: list<string>,
 *      product_id?: list<string>,
 *      sku?: list<string>
 * }
 */
class ProductService extends AbstractService
{
    private $path = '/v3/product';

    /**
     * This method allows you to create products and update their details.
     *
     * @see https://docs.ozon.ru/api/seller/en/#operation/ProductAPI_ImportProductsV3
     *
     * @param array $income Single item structure or array of items
     *
     * @return array
     */
    public function import(array $income, bool $validateBeforeSend = true)
    {
        if (!array_key_exists('items', $income)) {
            $income = $this->ensureCollection($income);
            $income = ['items' => $income];
        }

        $income = ArrayHelper::pick($income, ['items']);

        if ($validateBeforeSend) {
            $pv = new ProductValidator('create', 3);
            foreach ($income['items'] as &$item) {
                $item = $pv->validateItem($item);
            }
        }

        return $this->request('POST', "{$this->path}/import", $income);
    }
    
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
     * @deprecated use V4\ProductService::infoStocks
     *
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

    /**
     * Method for getting a list of all products.
     *
     * @see https://docs.ozon.ru/api/seller/en/#operation/ProductAPI_GetProductListv3
     *
     * @param TProductListRequestFilter $filter
     *
     * @return TProductListResponse
     */
    public function list(array $filter, string $lastId = '', int $limit = 100): array
    {
        $body = [
            'filter'  => ArrayHelper::pick($filter, ['offer_id', 'product_id', 'visibility']) ?: new \stdClass(),
            'last_id' => $lastId,
            'limit'   => $limit,
        ];

        return $this->request('POST', "{$this->path}/list", $body);
    }

    /**
     * Method for getting an array of products by their identifiers.
     *
     * @see https://docs.ozon.ru/api/seller/en/#operation/ProductAPI_GetProductInfoList
     *
     * @param TProductInfoListRequest $query
     *
     * @return TProductInfoListResponse
     */
    public function infoList(array $query): array
    {
        $query = ArrayHelper::pick($query, ['product_id', 'sku', 'offer_id']);
        $query = TypeCaster::castArr($query, [
            'product_id' => 'arrayOfString',
            'sku'        => 'arrayOfString',
            'offer_id'   => 'arrayOfString',
        ]);

        if (empty($query)) {
            throw new \InvalidArgumentException('Empty query provided');
        }

        return $this->request('POST', "{$this->path}/info/list", $query);
    }
}
