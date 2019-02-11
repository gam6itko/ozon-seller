<?php

namespace Gam6itko\OzonSeller\Service;

use Gam6itko\OzonSeller\Enum\DeliverySchema;

class OrderService extends AbstractService
{
    /**
     * Receive order info.
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_get_order
     * @param int $orderId
     * @param array $query ['translit']
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function info(int $orderId, array $query = [])
    {
        $query = $this->faceControl($query, ['translit']);

        return $this->request('GET', "/v1/order/{$orderId}", ['query' => $query]);
    }

    /**
     * Receive the list of orders.
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_post_order_list_all
     * @param \DateTimeInterface $since
     * @param \DateTimeInterface $to
     * @param string $deliverySchema
     * @param array $query ['page', 'page_size', 'status']
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function list(\DateTimeInterface $since, \DateTimeInterface $to, string $deliverySchema = DeliverySchema::CROSSBOARDER, array $query = []): array
    {
        $query = $this->faceControl($query, ['page', 'page_size', 'status']);

        $arr = array_merge([
            'since'           => $since->format(DATE_RFC3339),
            'to'              => $to->format(DATE_RFC3339),
            'delivery_schema' => $deliverySchema
        ], $query);

        return $this->request('POST', "/v1/order/list", ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * Approve items in the order. Changes approval status for specific items in the order.
     * @param int $orderId Order ID
     * @param array $itemIds List of unique item IDs in the order
     * @return bool
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_post_order_crossborder
     */
    public function itemsApprove(int $orderId, array $itemIds)
    {
        $arr = [
            "order_id" => $orderId,
            "item_ids" => $itemIds
        ];
        return $this->request('POST', "/v1/order/items/approve/crossborder", ['body' => \GuzzleHttp\json_encode($arr)]);
    }

    /**
     * Receive the list of available shipping providers. Only for Crossborder.
     * @return array
     * @throws \Exception
     */
    public function shippingProviders()
    {
        return $this->request('POST', "/v1/order/shipping-provider/list");
    }

    /**
     * Create a package, mark it as dispatched and provide a tracking number.
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_post_order_ship_cb
     * @param int $orderId Order ID
     * @param string $track Shipment tracking number
     * @param int $shippingProviderId Shipping company (provider) ID
     * @param array $items Order items array
     * @return array|string
     * @throws \Exception
     */
    public function shipCrossboarder(int $orderId, string $track, int $shippingProviderId, array $items)
    {
        $query = [
            'order_id'             => $orderId,
            'tracking_number'      => $track,
            'shipping_provider_id' => $shippingProviderId,
            'items'                => $items
        ];

        return $this->request('POST', "/v1/order/ship/crossborder", ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Create a package, mark it as dispatched and provide a tracking number.  Only for Fulfilled by Seller (FBS).
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_post_order_ship_fbs
     * @param int $orderId Order ID
     * @return array|string
     * @throws \Exception
     */
    public function shipFbs(int $orderId)
    {
        $query = [
            'order_id' => $orderId
        ];

        return $this->request('POST', "/v1/order/ship/crossborder", ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Will cancel an item in the order, requires cancellation reason to be provided.
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_post_order_item_crossborder
     * @param int $orderId Order ID
     * @param int $reasonCode Cancellation reason code
     * @param array $itemsIds List of unique item IDs in the order
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function itemsCancelCrossboarder(int $orderId, int $reasonCode, array $itemsIds)
    {
        $query = [
            'order_id'    => $orderId,
            'reason_code' => $reasonCode,
            'item_ids'    => $itemsIds
        ];

        return $this->request('POST', "/v1/order/items/cancel/crossborder", ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Will cancel an order, requires cancellation reason to be provided.
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_post_order_item_fbs
     * @param int $orderId Order ID
     * @param int $reasonCode Cancellation reason code
     * @return array|string
     * @throws \Exception
     */
    public function itemsCancelFbs(int $orderId, int $reasonCode)
    {
        $query = [
            'order_id'         => $orderId,
            'cancel_reason_id' => $reasonCode,
        ];

        return $this->request('POST', "/v1/order/cancel/fbs", ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Receive the list of unfulfilled orders, within the specified order date and time.
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_get_order_unfulfilled
     * @param array $query ['page', 'page_size']
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function unfulfilled(array $query = [])
    {
        $query = $this->faceControl($query, ['page', 'page_size']);

        return $this->request('POST', "/v1/order/unfulfilled", ['query' => $query]);
    }

    /**
     * Receive the list of canceled orders.
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_get_order_canceled
     * @param array $query ['page', 'page_size']
     * @return array|string
     * @throws \Exception
     */
    public function canceled(array $query = [])
    {
        $query = $this->faceControl($query, ['page', 'page_size']);

        return $this->request('POST', "/v1/order/canceled", ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_get_order_canceled
     */
    public function itemsCancelReasons()
    {
        return $this->request('POST', "/v1/order/cancel-reason/list");
    }
}