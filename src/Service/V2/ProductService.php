<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V2;

use Gam6itko\OzonSeller\ProductValidator;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\TypeCaster;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class ProductService extends AbstractService
{
    private $path = '/v2/product';

    /**
     * Creates product page in our system.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_product_import
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
            $pv = new ProductValidator('create', 2);
            foreach ($income['items'] as &$item) {
                $item = $pv->validateItem($item);
            }
        }

        return $this->request('POST', "{$this->path}/import", $income);
    }

    /**
     * Receive the list of products.
     *
     * query['filter']
     *          [offer_id] string|int|array
     *          [product_id] string|int|array,
     *          [visibility] string
     *      [last_id] str
     *      [limit] int
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_products_list
     */
    public function list(array $query)
    {
        $query = ArrayHelper::pick($query, ['filter', 'last_id', 'limit']);
        $query = TypeCaster::castArr($query, ['last_id' => 'str', 'limit' => 'int']);
        if (isset($query['filter'])) {
            $query['filter'] = TypeCaster::castArr(
                ArrayHelper::pick($query['filter'], ['offer_id', 'product_id', 'visibility']),
                ['offer_id' => 'arrOfStr', 'product_id' => 'arrOfInt', 'visibility' => 'str']
            );
        }

        return $this->request('POST', "{$this->path}/list", $query);
    }

    /**
     * Receive product info.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_products_info
     *
     * @param array $query ['product_id', 'sku', 'offer_id']
     */
    public function info(array $query): array
    {
        $query = ArrayHelper::pick($query, ['product_id', 'sku', 'offer_id']);
        $query = TypeCaster::castArr($query, ['product_id' => 'int', 'sku' => 'int', 'offer_id' => 'str']);

        return $this->request('POST', "{$this->path}/info", $query);
    }

    public function infoList(array $query): array
    {
        $query = ArrayHelper::pick($query, ['product_id', 'sku', 'offer_id']);
        $query = TypeCaster::castArr($query, ['product_id' => 'arrOfInt', 'sku' => 'arrOfInt', 'offer_id' => 'arrOfStr']);

        return $this->request('POST', "{$this->path}/info/list", $query);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_products_info_attributes
     */
    public function infoAttributes(array $filter, int $page = 1, int $pageSize = 100): array
    {
        $keys = ['offer_id', 'product_id'];
        $filter = ArrayHelper::pick($filter, $keys);

        foreach ($keys as $k) {
            if (isset($filter[$k]) && !is_array($filter[$k])) {
                $filter[$k] = [$filter[$k]];
            }
        }

        if (isset($filter['offer_id'])) {
            $filter['offer_id'] = array_map('strval', $filter['offer_id']);
        }

        $query = [
            'filter'    => $filter,
            'page'      => $page,
            'page_size' => $pageSize,
        ];

        return $this->request('POST', "{$this->path}s/info/attributes", $query);
    }

    /**
     * Receive products stocks info.
     *
     * @param array $pagination ['page', 'page_size']
     *
     * @return array {items: array, total: int}
     *
     * @see https://docs.ozon.ru/api/seller/#operation/ProductAPI_GetProductInfoPricesV2
     */
    public function infoStocks(array $pagination = []): array
    {
        $pagination = array_merge(
            ['page' => 1, 'page_size' => 100],
            ArrayHelper::pick($pagination, ['page', 'page_size'])
        );

        return $this->request('POST', "{$this->path}/info/stocks", $pagination);
    }

    /**
     * Receive products prices info.
     *
     * @see https://docs.ozon.ru/api/seller/#operation/ProductAPI_GetProductInfoListV2
     *
     * @param array $pagination [page, page_size]
     *
     * @return array
     */
    public function infoPrices(array $pagination = [])
    {
        $pagination = array_merge(
            ['page' => 1, 'page_size' => 100],
            ArrayHelper::pick($pagination, ['page', 'page_size'])
        );

        return $this->request('POST', '/v1/product/info/prices', $pagination);
    }

    /**
     * Update product stocks.
     *
     * @see https://docs.ozon.ru/api/seller/#operation/ProductAPI_ProductsStocksV2
     *
     * @param $input
     *
     * @return array
     */
    public function importStocks(array $input)
    {
        if (empty($input)) {
            throw new \InvalidArgumentException('Empty stocks data');
        }

        if ($this->isAssoc($input) && !isset($input['stocks'])) {// if it one price
            $input = ['stocks' => [$input]];
        } else {
            if (!$this->isAssoc($input)) {// if it plain array on prices
                $input = ['stocks' => $input];
            }
        }

        if (!isset($input['stocks'])) {
            throw new \InvalidArgumentException();
        }

        foreach ($input['stocks'] as $i => &$s) {
            if (!$s = ArrayHelper::pick($s, ['product_id', 'offer_id', 'stock', 'warehouse_id'])) {
                throw new \InvalidArgumentException('Invalid stock data at index '.$i);
            }

            $s = TypeCaster::castArr(
                $s,
                [
                    'product_id' => 'int',
                    'offer_id'   => 'str',
                    'stock'      => 'int',
                    'warehouse_id' => 'int'
                ]
            );
        }

        return $this->request('POST', "{$this->path}s/stocks", $input);
    }

    /**
     * @param array $input one of: <br>
     *                     {products:[{offer_id: "str"}, ...]}<br>
     *                     [{offer_id: "str"}, ...]<br>
     *                     {offer_id: "str"}<br>
     *
     * @see https://docs.ozon.ru/api/seller/#operation/ProductAPI_DeleteProducts
     */
    public function delete(array $input)
    {
        if ($this->isAssoc($input) && !isset($input['products'])) {// if it one price
            $input = ['products' => [$input]];
        } else {
            if (!$this->isAssoc($input)) {// if it plain array on prices
                $input = ['products' => $input];
            }
        }

        if (!isset($input['products'])) {
            throw new \InvalidArgumentException();
        }

        foreach ($input['products'] as $i => &$s) {
            if (!$s = ArrayHelper::pick($s, ['offer_id'])) {
                throw new \InvalidArgumentException('Invalid stock data at index '.$i);
            }

            $s = TypeCaster::castArr(
                $s,
                [
                    'offer_id' => 'str',
                ]
            );
        }

        return $this->request('POST', "{$this->path}s/delete", $input);
    }
}
