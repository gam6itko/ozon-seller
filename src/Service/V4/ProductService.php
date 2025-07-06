<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V4;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

/**
 * @psalm-type TInfoStocksQuantRequestFilter = array{
 *      created: bool,
 *      exists: bool
 * }
 * @psalm-type TInfoStocksRequestFilter = array{
 *      offer_id?: list<string>,
 *      product_id?: list<string>,
 *      visibility?: string,
 *      with_quant?: TInfoStocksQuantRequestFilter
 * }
 * @psalm-type TInfoStocksResponseStocks = array{
 *      present: int,
 *      reserved: int,
 *      shipment_type: string,
 *      sku: int,
 *      type: string
 * }
 * @psalm-type TInfoStocksResponseItem = array{
 *      offer_id: string,
 *      product_id: int,
 *      stocks: list<TInfoStocksResponseStocks>
 * }
 * @psalm-type TInfoStocksResponse = array{
 *      items: list<TInfoStocksResponseItem>,
 *      cursor: string,
 *      total: int
 * }
 * @psalm-type TInfoAttributesResponseItem = array{
 *      id: int,
 *      barcode: string,
 *      barcodes: list<string>,
 *      name: string,
 *      offer_id: string,
 *      type_id: int,
 *      height: int,
 *      depth: int,
 *      width: int,
 *      dimension_unit: string,
 *      weight: int,
 *      weight_unit: string,
 *      primary_image: string,
 *      sku: int,
 *      model_info: array{
 *          model_id: int,
 *          count: int
 *      },
 *      images: list<string>,
 *      pdf_list: list<array>,
 *      attributes: list<array{
 *          id: int,
 *          complex_id: int,
 *          values: list<array{
 *              dictionary_value_id: int,
 *              value: string
 *          }>
 *      }>,
 *      attributes_with_defaults: list<int>,
 *      complex_attributes: list<array>,
 *      color_image: string,
 *      description_category_id: int
 *  }
 *
 * @psalm-type TInfoAttributesRequestFilter = array{
 *      offer_id?: list<string>,
 *      product_id?: list<numeric-string>,
 *      sku?: list<numeric-string>,
 *      visibility?: string,
 *  }
 * @psalm-type TInfoAttributesResponse = array{
 *      result: list<TInfoAttributesResponseItem>,
 *      last_id: string,
 *      total: numeric-string
 *  }
 */
class ProductService extends AbstractService
{
    private $path = '/v4/product';

    /**
     * @deprecated use V5\ProductService::infoPrices
     * @see https://api-seller.ozon.ru/v4/product/info/prices
     */
    public function infoPrices(array $filter, ?string $lastId = '', int $limit = 100)
    {
        assert($limit > 0 && $limit <= 1000);

        $body = [
            'filter'  => ArrayHelper::pick($filter, ['offer_id', 'product_id', 'visibility']) ?: new \stdClass(),
            'last_id' => $lastId ?? '',
            'limit'   => $limit,
        ];

        return $this->request('POST', "{$this->path}/info/prices", $body);
    }

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/ProductAPI_GetUploadQuota
     *
     * @psalm-type TQuota = array{
     *      limit: int,
     *      reset_at: string,
     *      usage: int
     * }
     *
     * @return array{
     *     daily_create: TQuota,
     *     daily_update: TQuota,
     *     total: array{
     *          limit: int,
     *          usage: int,
     *     },
     * }
     */
    public function infoLimit(): array
    {
        return $this->request('POST', "{$this->path}/info/limit", '{}');
    }

    /**
     * Returns information about the quantity of products:
     *  - how many items are available,
     *  - how many are reserved by customers.
     *
     * @see https://docs.ozon.ru/api/seller/en/#operation/ProductAPI_GetProductInfoStocks
     *
     * @param TInfoStocksRequestFilter $filter
     *
     * @return TInfoStocksResponse
     */
    public function infoStocks(array $filter, string $cursor = '', int $limit = 100): array
    {
        $body = [
            'filter'  => ArrayHelper::pick($filter, ['offer_id', 'product_id', 'visibility', 'with_quant']) ?: new \stdClass(),
            'cursor'  => $cursor,
            'limit'   => $limit,
        ];

        return $this->request('POST', "{$this->path}/info/stocks", $body);
    }

    /**
     * Returns a product characteristics description by product identifier or visibility.
     *
     * @see https://docs.ozon.ru/api/seller/en/#operation/ProductAPI_GetProductAttributesV4
     *
     * @param TInfoAttributesRequestFilter $filter
     *
     * @return TInfoAttributesResponse
     */
    public function infoAttributes(
        array $filter = [],
        ?string $lastId = null,
        int $limit = 100,
        ?string $sortBy = null,
        ?string $sortDir = null
    ) {
        $body = [
            'filter' => ArrayHelper::pick($filter, ['offer_id', 'product_id', 'sku', 'visibility']) ?: new \stdClass(),
            'limit' => $limit,
        ];

        if ($lastId) {
            $body['last_id'] = $lastId;
        }

        if ($sortBy) {
            $body['sort_by'] = $sortBy;
        }

        if ($sortDir) {
            $body['sort_dir'] = $sortDir;
        }

        return $this->request('POST', "{$this->path}/info/attributes", $body, true, false);
    }
}
