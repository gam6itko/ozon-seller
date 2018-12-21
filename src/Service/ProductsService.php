<?php

namespace Gam6itko\OzonSeller\Service;

class ProductsService extends AbstractService
{
    /**
     * @param $product
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function create(array $product)
    {
        return $this->request('POST', "/v1/products/create", ['body' => \GuzzleHttp\json_encode($product)]);
    }

    /**
     * @param int $productId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function info(int $productId)
    {
        return $this->request('GET', "/v1/products/info/{$productId}");
    }

    /**
     * @param $product
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function update(array $product)
    {
        return $this->request('POST', "/v1/products/update", ['body' => \GuzzleHttp\json_encode($product)]);
    }

    /**
     * @param int $productId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function activate(int $productId): bool
    {
        $response = $this->request('POST', "/v1/products/activate", ['body' => \GuzzleHttp\json_encode(['product_id' => $productId])]);
        return 'success' === $response;
    }

    /**
     * @param int $productId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function deactivate(int $productId): bool
    {
        $response = $this->request('POST', "/v1/products/deactivate", ['body' => \GuzzleHttp\json_encode(['product_id' => $productId])]);
        return 'success' === $response;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function list(int $page = 0, int $perPage = 1000)
    {
        return $this->request('GET', "/v1/products/list", ['query' => ['page' => $page, 'per_page' => $perPage]]);
    }

    /**
     * @param $pricesList
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function updatePrices(array $pricesList)
    {
        $arr = ['prices' => $pricesList];
        return $this->request('POST', "/v1/products/prices", ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * @param $stocksList
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function updateStocks(array $stocksList)
    {
        $arr = ['stocks' => $stocksList];
        return $this->request('POST', "/v1/products/stocks", ['body' => \GuzzleHttp\json_encode($arr)]);
    }
}