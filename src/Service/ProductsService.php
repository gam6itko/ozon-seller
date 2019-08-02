<?php

namespace Gam6itko\OzonSeller\Service;

use Gam6itko\OzonSeller\ProductValidator;
use Gam6itko\OzonSeller\TypeCaster;
use Prophecy\Exception\Exception;

class ProductsService extends AbstractService
{
    /**
     * Automatically determines a product category for a product
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_product_classifier
     * @param array $income
     * @return array|string
     * @throws \Exception
     */
    public function classify(array $income)
    {
        if (!array_key_exists('products', $income)) {
            $income = $this->ensureCollection($income);
            $income = ['products' => $income];
        }

        $income = $this->faceControl($income, ['products']);
        foreach ($income['products'] as &$p) {
            $p = $this->faceControl($p, [
                "offer_id", "shop_category_full_path", "shop_category", "shop_category_id", "vendor", "model", "name",
                "price", "offer_url", "img_url", "vendor_code", "barcode"
            ]);
        }

        return $this->request('POST', "/v1/product/classify", ['body' => \GuzzleHttp\json_encode($income)]);
    }

    /**
     * Creates product page in our system
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_products_create
     * @param array $income
     * @param bool $validate
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function create(array $income, bool $validate = true)
    {
        if (!array_key_exists('items', $income)) {
            $income = $this->ensureCollection($income);
            $income = ['items' => $income];
        }

        $income = $this->faceControl($income, ['items']);

        if ($validate) {
            $pv = new ProductValidator('create');
            foreach ($income['items'] as &$item) {
                $item = $pv->validateItem($item);
            }
        }

        return $this->request('POST', "/v1/product/import", ['body' => \GuzzleHttp\json_encode($income)]);
    }

    /**
     * Receive product info.
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_products_info
     * @param int $productId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function info(int $productId)
    {
        $query = ['product_id' => $productId];
        $query = TypeCaster::castArr($query, ['product_id' => 'int']);

        return $this->request('POST', "/v1/product/info", ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Receive product info.
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_products_info
     * @param array $query ['product_id', 'sku', 'offer_id']
     * @return array|string
     * @throws \Exception
     */
    public function infoBy(array $query)
    {
        $query = $this->faceControl($query, ['product_id', 'sku', 'offer_id']);
        $query = TypeCaster::castArr($query, ['product_id' => 'int', 'sku' => 'int', 'offer_id' => 'str']);
        return $this->request('POST', "/v1/product/info", ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Receive the list of products.
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_products_list
     * @param array $filter
     * @param array $pagination
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function list(array $filter = [], array $pagination = [])
    {
        $filter = $this->faceControl($filter, ['offer_id', 'product_id', 'visibility']);
        $pagination = $this->faceControl($pagination, ['page', 'page_size']);

        $query = array_filter(array_merge($pagination, ['filter' => $filter]));

        return $this->request('POST', "/v1/product/list", ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Update the price for one or multiple products.
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_products_prices
     * @param $prices
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function updatePrices(array $prices)
    {
        foreach ($prices as &$p) {
            $p = $this->faceControl($p, ['product_id', 'price', 'old_price', 'vat']);
        }

        $arr = ['prices' => $prices];
        return $this->request('POST', "/v1/product/import/prices", ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * Update product stocks.
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_products_stocks
     * @param $stocks
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function updateStocks(array $stocks)
    {
        if (array_key_exists('stocks', $stocks)) {
            trigger_error('You should pass stoks arg without stocks key', E_USER_NOTICE);
            $stocks = $stocks['stocks'];
        }

        foreach ($stocks as &$s) {
            $s = $this->faceControl($s, ['product_id', 'stock']);
        }

        $arr = ['stocks' => $stocks];
        return $this->request('POST', "/v1/product/import/stocks", ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * Change the product info. Please note, that you cannot update price and stocks.
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_products_prices
     * @param array $product
     * @param bool $validate
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function update(array $product, bool $validate = true)
    {
        if ($validate) {
            $pv = new ProductValidator('create');
            $product = $pv->validateItem($product);
        }

        return $this->request('POST', "/v1/products/update", ['body' => \GuzzleHttp\json_encode($product)]);
    }

    /**
     * Mark the product as in stock.
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_products_activate
     * @param int $productId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function activate(int $productId): bool
    {
        $response = $this->request('POST', "/v1/product/activate", ['body' => \GuzzleHttp\json_encode(['product_id' => $productId])]);
        return 'success' === $response;
    }

    /**
     * Mark the product as not in stock.
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_products_deactivate
     * @param int $productId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function deactivate(int $productId): bool
    {
        $response = $this->request('POST', "/v1/product/deactivate", ['body' => \GuzzleHttp\json_encode(['product_id' => $productId])]);
        return 'success' === $response;
    }
}