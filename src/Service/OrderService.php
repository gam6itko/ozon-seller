<?php

namespace Gam6itko\OzonSeller\Service;

use Gam6itko\OzonSeller\Enum\DeliverySchema;

class OrderService extends AbstractService
{
    /**
     * @param \DateTime $since
     * @param \DateTime $to
     * @param string $deliverySchema
     * @param array $query
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function list(\DateTime $since, \DateTime $to, string $deliverySchema = DeliverySchema::CROSSBOARDER, array $query = []): array
    {
        $whitelist = ['page', 'page_size'];
        $query = array_intersect_key($query, array_flip($whitelist));

        $arr = array_merge([
            'since'           => $since->format(DATE_RFC3339),
            'to'              => $to->format(DATE_RFC3339),
            'delivery_schema' => $deliverySchema
        ], $query);

        return $this->request('GET', "/v1/orders/list", ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * @param int $orderId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @see http://cb-api.test.ozon.ru/apiref/ru/#t-title_get_order
     */
    public function info(int $orderId)
    {
        return $this->request('GET', "/v1/order/{$orderId}");
    }

    /**
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @see http://cb-api.test.ozon.ru/apiref/ru/#t-title_post_order_ship_cb
     */
    public function ship($data)
    {
        $whitelist = ['order_id', 'tracking_number', 'shipping_provider_id', 'items'];
        $data = array_intersect_key($data, array_flip($whitelist));
        return $this->request('POST', "/v1/order/ship/crossborder", ['body' => \GuzzleHttp\json_encode($data)]);
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