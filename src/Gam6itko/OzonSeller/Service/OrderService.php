<?php
namespace Gam6itko\OzonSeller\Service;

class OrderService extends AbstractService
{
    /**
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @http://cb-api.test.ozon.ru/apiref/ru/#t-title_post_order_ship
     */
    public function ship($data)
    {
        return $this->request('POST', "/v1/order/ship", ['body' => \GuzzleHttp\json_encode($data)]);
    }

    /**
     * @param int $orderId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function info(int $orderId)
    {
        return $this->request('GET', "/v1/order/{$orderId}");
    }

    /**
     * Fulfilled by Ozon
     * @param \DateTime $since
     * @param \DateTime $to
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function listFbo(\DateTime $since, \DateTime $to): array
    {
        $arr = [
            'since' => $since->format('Y-m-d'),
            'to'    => $to->format('Y-m-d'),
        ];
        return $this->request('GET', "/v1/orders/list/fbo", ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * Fulfilled by Seller
     * @param \DateTime $since
     * @param \DateTime $to
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function listFbs(\DateTime $since, \DateTime $to): array
    {
        $arr = [
            'since' => $since->format('Y-m-d'),
            'to'    => $to->format('Y-m-d'),
        ];
        return $this->request('GET', "/v1/orders/list/fbs", ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * @param \DateTime $since
     * @param \DateTime $to
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function listCrossborder(\DateTime $since, \DateTime $to): array
    {
        $arr = [
            'since' => $since->format('Y-m-d'),
            'to'    => $to->format('Y-m-d'),
        ];
        return $this->request('GET', "/v1/orders/list/crossborder", ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * @param int $orderId
     * @param array $itemIds
     * @return bool
     */
    public function itemsApprove(int $orderId, array $itemIds)
    {
        $arr = [
            "order_id" => $orderId,
            "item_ids" => $itemIds
        ];
        return $this->request('POST', "/v1/order/items/approve", ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_post_order_item_crossborder
     */
    public function itemsCancel($data)
    {
        return $this->request('POST', "/v1/order/items/cancel", ['body' => \GuzzleHttp\json_encode($data)]);
    }

    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @see http://cb-api.test.ozon.ru/apiref/ru/#t-title_get_order_canceled
     */
    public function itemsCancelReasons()
    {
        return $this->request('GET', "/v1/order/items/cancel-reasons");
    }

    /**
     * @param int $limit
     * @param int $start
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function unfulfilled(int $limit = 10, int $start = 0)
    {
        $query = [
            'limit' => $limit,
            'start' => $start
        ];
        return $this->request('GET', "/v1/order/unfulfilled", ['query' => $query]);
    }

    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function canceled()
    {
        return $this->request('GET', "/v1/order/canceled");
    }

    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function shippingProviders()
    {
        return $this->request('GET', "/v1/shipping-providers");
    }
}