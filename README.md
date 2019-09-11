# Documentation

<https://cb-api.ozonru.me/apiref/>

# Example
For more examples look at `tests`

## Categories

```php
use Gam6itko\OzonSeller\Service\CategoriesService;

$clientId = '<ozon seller client-id>';
$apiKey = '<ozon seller api-key>';
$sandboxHost = 'http://cb-api.ozonru.me/';

$svc = new CategoriesService($clientId, $apiKey, $sandboxHost);

//Server Response example: https://cb-api.ozonru.me/apiref/en/#t-title_categories
$categoryTree = $svc->tree();

//Server Response example: https://cb-api.ozonru.me/apiref/en/#t-title_get_categories_attributes
$attributes = $svc->attributes(17038826);
```

## Order

### info

`/v1/order/{order-number}`

```php
$svcOrder = new OrderService($clientId, $apiKey, $sandboxHost);

$orderId = '12345678-1234'; // ozon client oreder like \d{8}-\d{4}
$orderArr = $svcOrder->info($orderId);
echo json_encode($orderArr);
```

```json
{
  "order_id": 123456,
  "order_nr": "12345678-1234",
  "status": "delivered",
  "customer_id": 122334,
  "delivery_schema": "fbo",
  "last_updated": "2018-09-25T12:41:48.932Z",
  "order_time": "2018-09-25T12:41:48.932Z",
  "address": {
    "address_tail": "Vlogogradskaya st. 12 - 23",
    "addressee": "Ivan Ivanov",
    "city": "Moscow",
    "comment": "pass code #123",
    "country": "Russia",
    "district": "Central",
    "phone": "7903XXXXXXX",
    "email": "test@ozon.ru",
    "region": "Moscow",
    "zip_code": "112334"
  },
  "items": [
    {
      "product_id": 124525,
      "item_id": 325441,
      "quantity": 1,
      "offer_id": "124100",
      "price": "79999",
      "tracking_number": "XZY1111111",
      "status": "delivered",
      "cancel_reason_id": 0,
      "auto_cancel_date": "2019-01-09T09:56:53.587Z",
      "shipping_provider_id": 5
    }
  ]
}
```


### order approve

`/v1/order/approve/crossborder`

```php
$orderId = '12345678-1234'; // ozon client oreder like \d{8}-\d{4}
$isSuccess = $svcOrder->approve($orderId);
```

### ship crossborder

`/v1/order/ship/crossborder`

```php
$orderId = '12345678-1234'; // ozon client oreder like \d{8}-\d{4}
$track = 'TRACK_NUMBER_1234';
$shippingProviderId = 1;
$items = [
  [
    'item_id'  => 123,
    'quantity' => 1
  ]
];
$isSuccess = $svcOrder->shipCrossboarder($orderId, $track, $shippingProviderId, $items);
```

### itemsCancelReasons

`/v1/order/cancel-reason/list`

```php
$cancelReasons = $svcOrder->itemsCancelReasons();
```

```json
[
  {
    "id": 352,
    "title": "Product is out of stock"
  },
  {
    "id": 353,
    "title": "Product with wrong price"
  },
  {
    "id": 358,
    "title": "Canceled by seller"
  }
]
```


### cancel crossborder

`/v1/order/items/cancel/crossborder`

```php
$reasonCode = 352;
$itemIds = [123, 456];
$isSuccess = $svcOrder->itemsCancelCrossboarder($orderId, $reasonCode, $itemIds);
```


## Products

### create

`/v1/product/import`

```php
$svcProduct = new ProductsService($clientId, $apiKey, $sandboxHost);
$product = [
    'barcode'        => '8801643566784',
    'description'    => 'Red Samsung Galaxy S9 with 512GB',
    'category_id'    => 17030819,
    'name'           => 'Samsung Galaxy S9',
    'offer_id'       => 'REDSGS9-512',
    'price'          => '79990',
    'old_price'      => '89990',
    'premium_price'  => '75555',
    'vat'            => '0',
    'vendor'         => 'Samsung',
    'vendor_code'    => 'SM-G960UZPAXAA',
    'height'         => 77,
    'depth'          => 11,
    'width'          => 120,
    'dimension_unit' => 'mm',
    'weight'         => 120,
    'weight_unit'    => 'g',
    'images'         => [
        [
            'file_name' => 'https://ozon-st.cdn.ngenix.net/multimedia/c1200/1022555115.jpg',
            'default'   => true,
        ],
        [
            'file_name' => 'https://ozon-st.cdn.ngenix.net/multimedia/c1200/1022555110.jpg',
            'default'   => false,
        ],
        [
            'file_name' => 'https://ozon-st.cdn.ngenix.net/multimedia/c1200/1022555111.jpg',
            'default'   => false,
        ],
    ],
    'attributes'     => [
        [
            'id'    => 8229,
            'value' => '4747',
        ],
        [
            'id'    => 9048,
            'value' => 'Samsung Galaxy S9',
        ],
        [
            'id'    => 4742,
            'value' => '512 ГБ',
        ],

        [
            'id'         => 4413,
            'collection' => ['1', '2', '13'],
        ],
        [
            'id'                 => 4018,
            'complex_collection' => [
                [
                    'collection' => [
                        [
                            'id'    => 4068,
                            'value' => 'Additional video',
                        ],
                        [
                            'id'    => 4074,
                            'value' => '5_-NKRVn7IQ',
                        ],
                    ],
                ],
                [
                    'collection' => [
                        [
                            'id'    => 4068,
                            'value' => 'Another one video',
                        ],
                        [
                            'id'    => 4074,
                            'value' => '5_-NKRVn7IQ',
                        ],
                    ],
                ],
            ],
        ],
    ],
];

$svcProduct->create($product);
// or
$svcProduct->create([$product, $product1, $product2, ...]);
// or
$svcProduct->create(['items' => [$product, $product1, $product2, ...] ]);
```