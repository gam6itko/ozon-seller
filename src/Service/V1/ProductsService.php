<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1;

use Gam6itko\OzonSeller\ProductValidator;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\TypeCaster;
use Gam6itko\OzonSeller\Exception\BadRequestException;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class ProductsService extends AbstractService
{
    /**
     * Automatically determines a product category for a product.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_product_classifier
     *
     * @param array $income Single product structure or array of structures
     *
     * @return array
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
                'offer_id',
                'shop_category_full_path',
                'shop_category',
                'shop_category_id',
                'vendor',
                'model',
                'name',
                'price',
                'offer_url',
                'img_url',
                'vendor_code',
                'barcode',
            ]);
        }

        return $this->request('POST', '/v1/product/classify', $income);
    }

    /**
     * Creates product page in our system.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_products_create
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
            $pv = new ProductValidator('create');
            foreach ($income['items'] as &$item) {
                $item = $pv->validateItem($item);
            }
        }

        // cast attributes types.
        foreach ($income['items'] as &$item) {
            if (isset($item['attributes']) && is_array($item['attributes']) && count($item['attributes']) > 0) {
                foreach ($item['attributes'] as &$attribute) {
                    $attribute = TypeCaster::castArr($attribute, ['value' => 'str']);
                    if (isset($item['collection']) && is_array($attribute['collection']) && count($attribute['collection']) > 0) {
                        foreach ($attribute['collection'] as &$collectionItem) {
                            $collectionItem = (string) $collectionItem;
                        }
                    }
                }
            }
        }

        return $this->request('POST', '/v1/product/import', $income);
    }

    /**
     * @param array $income Single item structure or array of item
     *
     * @return array|string
     */
    public function importBySku(array $income)
    {
        if (!array_key_exists('items', $income)) {
            $income = $this->ensureCollection($income);
            $income = ['items' => $income];
        }

        $income = $this->faceControl($income, ['items']);
        foreach ($income['items'] as &$item) {
            $item = TypeCaster::castArr(
                $this->faceControl($item, ['sku', 'name', 'offer_id', 'price', 'old_price', 'premium_price', 'vat']),
                [
                    'offer_id'      => 'str',
                    'price'         => 'str',
                    'old_price'     => 'str',
                    'premium_price' => 'str',
                    'vat'           => 'str',
                ]
            );
        }

        return $this->request('POST', '/v1/product/import-by-sku', $income);
    }

    /**
     * Product creation status.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_products_create_status
     *
     * @param int $taskId Product import task id
     *
     * @return array
     */
    public function importInfo(int $taskId)
    {
        $query = ['task_id' => $taskId];

        return $this->request('POST', '/v1/product/import/info', $query);
    }

    /**
     * Receive product info.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_products_info
     *
     * @param int $productId Id of product in Ozon system
     *
     * @return array
     */
    public function info(int $productId)
    {
        $query = ['product_id' => $productId];
        $query = TypeCaster::castArr($query, ['product_id' => 'int']);

        return $this->request('POST', '/v1/product/info', $query);
    }

    /**
     * Receive product info.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_products_info
     *
     * @param array $query ['product_id', 'sku', 'offer_id']
     *
     * @return array
     */
    public function infoBy(array $query)
    {
        $query = $this->faceControl($query, ['product_id', 'sku', 'offer_id']);
        $query = TypeCaster::castArr($query, ['product_id' => 'int', 'sku' => 'int', 'offer_id' => 'str']);

        return $this->request('POST', '/v1/product/info', $query);
    }

    /**
     * Receive products stocks info.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_get_product_info_stocks
     *
     * @param array $pagination ['page', 'page_size']
     *
     * @return array
     */
    public function infoStocks(array $pagination = [])
    {
        $pagination = array_merge(
            ['page' => 1, 'page_size' => 100],
            $this->faceControl($pagination, ['page', 'page_size'])
        );

        return $this->request('POST', '/v1/product/info/stocks', $pagination);
    }

    /**
     * Receive products prices info.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_get_product_info_prices
     *
     * @param array $pagination [page, page_size]
     *
     * @return array
     */
    public function infoPrices(array $pagination = [])
    {
        $pagination = array_merge(
            ['page' => 1, 'page_size' => 100],
            $this->faceControl($pagination, ['page', 'page_size'])
        );

        return $this->request('POST', '/v1/product/info/prices', $pagination);
    }

    /**
     * Receive the list of products.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_products_list
     *
     * @param array $filter     ['offer_id', 'product_id', 'visibility']
     * @param array $pagination ['page', 'page_size']
     *
     * @return array
     */
    public function list(array $filter = [], array $pagination = [])
    {
        $filter = $this->faceControl($filter, ['offer_id', 'product_id', 'visibility']);
        $pagination = $this->faceControl($pagination, ['page', 'page_size']);
        if (empty($pagination)) {
            $pagination = ['page' => 1, 'page_size' => 10];
        }
        $query = array_filter(array_merge($pagination, ['filter' => $filter]));

        return $this->request('POST', '/v1/product/list', $query);
    }

    /**
     * Update the price for one or multiple products.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_products_prices
     *
     * @param $input
     *
     * @return array
     */
    public function importPrices(array $input)
    {
        if (empty($input)) {
            throw new \InvalidArgumentException('Empty prices data');
        }

        if ($this->isAssoc($input) && !isset($input['prices'])) {// if it one price
            $input = ['prices' => [$input]];
        } else {
            if (!$this->isAssoc($input)) {// if it plain array on prices
                $input = ['prices' => $input];
            }
        }

        if (!isset($input['prices'])) {
            throw new \InvalidArgumentException();
        }

        foreach ($input['prices'] as $i => &$p) {
            if (!$p = $this->faceControl($p, ['product_id', 'offer_id', 'price', 'old_price', 'premium_price'])) {
                throw new \InvalidArgumentException('Invalid price data at index '.$i);
            }

            $p = TypeCaster::castArr(
                $p,
                [
                    'product_id'    => 'int',
                    'offer_id'      => 'str',
                    'price'         => 'str',
                    'old_price'     => 'str',
                    'premium_price' => 'str',
                ]
            );
        }

        return $this->request('POST', '/v1/product/import/prices', $input);
    }

    /**
     * Update product stocks.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_post_products_stocks
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
            if (!$s = $this->faceControl($s, ['product_id', 'offer_id', 'stock'])) {
                throw new \InvalidArgumentException('Invalid stock data at index '.$i);
            }

            $s = TypeCaster::castArr(
                $s,
                [
                    'product_id' => 'int',
                    'offer_id'   => 'str',
                    'stock'      => 'int',
                ]
            );
        }

        return $this->request('POST', '/v1/product/import/stocks', $input);
    }

    /**
     * Change the product info. Please note, that you cannot update price and stocks.
     *
     * @see  http://cb-api.ozonru.me/apiref/en/#t-title_post_products_prices
     *
     * @param array $product  Product structure
     * @param bool  $validate Perform validation before send
     *
     * @return array
     *
     * @todo return bool
     */
    public function update(array $product, bool $validate = true)
    {
        if ($validate) {
            $pv = new ProductValidator('update');
            $product = $pv->validateItem($product);
        }

        return $this->request('POST', '/v1/product/update', $product);
    }

    /**
     * @deprecated Mark the product as in stock.
     *
     * @param int $productId
     * @return bool success
     * @throws BadRequestException
     */
    public function activate(int $productId): bool
    {
        throw new BadRequestException(
            'API method /v1/product/activate is unavailable'
        );
    }

    /**
     * @deprecated Mark the product as not in stock.
     *
     * @param int $productId
     * @return bool success
     * @throws BadRequestException
     */
    public function deactivate(int $productId): bool
    {
        throw new BadRequestException(
            'API method /v1/product/deactivate is unavailable'
        );
    }

    /**
     * This method allows you to remove product in some cases: [product must not have active stocks, product should not have any sales].
     *
     * @return bool deleted
     *
     * @todo Documentation missed
     */
    public function delete(int $productId, string $offerId = null)
    {
        $query = array_filter([
            'product_id' => $productId,
            'offer_id'   => $offerId,
        ]);
        $response = $this->request('POST', '/v1/product/delete', $query);

        return 'deleted' === $response;
    }

    /**
     * Place product to archive.
     *
     * @see https://docs.ozon.ru/api/seller#/archive-post
     *
     * @param int $productId
     * @return bool
     */
    public function archive(int $productId): bool
    {
        $query = ['product_id' => [$productId]];

        return $this->request('POST', '/v1/product/archive', $query);
    }

    /**
     * Returns product from archive to store.
     *
     * @see https://docs.ozon.ru/api/seller#/unarchive-post
     *
     * @param int $productId
     * @return bool
     */
    public function unarchive(int $productId): bool
    {
        $query = ['product_id' => [$productId]];

        return $this->request('POST', '/v1/product/unarchive', $query);
    }

    /**
     * @see  https://github.com/gam6itko/ozon-seller/issues/6
     *
     * @param array $filter     ["offer_id": [], "product_id": [], "visibility": "ALL"]
     * @param array $pagination [page, page_size]
     *
     * @todo filter not works
     *
     * @return array
     */
    public function price(array $filter = [], array $pagination = [])
    {
        $filter = $this->faceControl($filter, ['offer_id', 'product_id', 'visibility']);
        $pagination = $this->faceControl($pagination, ['page', 'page_size']);
        $body = array_merge($pagination, [
//            'filter' => $filter,
        ]);

        return $this->request('POST', '/v1/product/list/price', $body);
    }

    /**
     * @see  https://cb-api.ozonru.me/apiref/en/#t-prepayment_set
     *
     * @param array $data ['is_prepayment', 'offers_ids', 'products_ids']
     *
     * @return array|string
     */
    public function setPrepayment(array $data)
    {
        $data = $this->faceControl($data, ['is_prepayment', 'offers_ids', 'products_ids']);

        return $this->request('POST', '/v1/product/prepayment/set', $data);
    }
}
