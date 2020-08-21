<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V2;

use Gam6itko\OzonSeller\ProductValidator;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\TypeCaster;

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

        $income = $this->faceControl($income, ['items']);

        if ($validateBeforeSend) {
            $pv = new ProductValidator('create', 2);
            foreach ($income['items'] as &$item) {
                $item = $pv->validateItem($item);
            }
        }

        return $this->request('POST', "{$this->path}/import", $income);
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
        $query = $this->faceControl($query, ['product_id', 'sku', 'offer_id']);
        $query = TypeCaster::castArr($query, ['product_id' => 'int', 'sku' => 'int', 'offer_id' => 'str']);

        return $this->request('POST', "{$this->path}/info", $query);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_products_info_attributes
     */
    public function infoAttributes(array $filter, int $page = 1, int $pageSize = 100): array
    {
        $keys = ['offer_id', 'product_id'];
        $filter = $this->faceControl($filter, $keys);

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

        return $this->request('POST', '/v2/products/info/attributes', $query);
    }
}
