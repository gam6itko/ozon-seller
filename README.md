# Ozon-seller API client
[![Build Status](https://travis-ci.com/gam6itko/ozon-seller.svg?branch=master)](https://travis-ci.com/gam6itko/ozon-seller)
[![Coverage Status](https://coveralls.io/repos/github/gam6itko/ozon-seller/badge.svg?branch=master)](https://coveralls.io/github/gam6itko/ozon-seller?branch=master)

[![Latest Stable Version](https://poser.pugx.org/gam6itko/ozon-seller/v)](//packagist.org/packages/gam6itko/ozon-seller) 
[![Total Downloads](https://poser.pugx.org/gam6itko/ozon-seller/downloads)](//packagist.org/packages/gam6itko/ozon-seller) 
[![Latest Unstable Version](https://poser.pugx.org/gam6itko/ozon-seller/v/unstable)](//packagist.org/packages/gam6itko/ozon-seller) 
[![License](https://poser.pugx.org/gam6itko/ozon-seller/license)](//packagist.org/packages/gam6itko/ozon-seller)

Документация Ozon Api: <https://cb-api.ozonru.me/apiref/>

## Установка

```shell
composer require gam6itko/ozon-seller
```

Для взаимодействия библиотеки с Ozon-Api используют [psr-18-client](https://packagist.org/explore/?tags=psr-18~client).

### Использование с Symfony
https://symfony.com/doc/current/components/http_client.html#psr-18-and-psr-17

```shell
composer require symfony/http-client
composer require nyholm/psr7
```

```php
use Gam6itko\OzonSeller\Service\V1\ProductsService;
use Symfony\Component\HttpClient\Psr18Client;

$config = [$_SERVER['CLIENT_ID'], $_SERVER['API_KEY'], $_SERVER['API_URL']];
$client = new Psr18Client();
$svc = new ProductsService($config, $client);
//do stuff
```

### Использование без Symfony

```shell
composer require php-http/guzzle6-adapter
```

```php
use Gam6itko\OzonSeller\Service\V1\CategoriesService;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

$config = [
    'clientId' => '<ozon seller client-id>',
    'apiKey' => '<ozon seller api-key>',
    'host' => 'http://cb-api.ozonru.me/'
];
$adapter = new GuzzleAdapter(new GuzzleClient());
$svc = new CategoriesService($config, $adapter);
//do stuff
```

## Примеры использования
Больше примеров смотрите в папке `tests`

## Categories

```php
use Gam6itko\OzonSeller\Service\V1\CategoriesService;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

$config = [
    'clientId' => '<ozon seller client-id>',
    'apiKey' => '<ozon seller api-key>',
    'host' => 'http://cb-api.ozonru.me/' //sandbox
];
$adapter = new GuzzleAdapter(new GuzzleClient());
$svc = new CategoriesService($config, $adapter);

//Server Response example: https://cb-api.ozonru.me/apiref/en/#t-title_categories
$categoryTree = $svc->tree();

//Server Response example: https://cb-api.ozonru.me/apiref/en/#t-title_get_categories_attributes
$attributes = $svc->attributes(17038826);
```

## Posting Crossborder

### get info 

`/v2/posting/crossborder/get`

```php
use Gam6itko\OzonSeller\Service\V2\Posting\CrossborderService;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

$config = [
    'clientId' => '<ozon seller client-id>',
    'apiKey' => '<ozon seller api-key>',
    'host' => 'http://cb-api.ozonru.me/'
];
$adapter = new GuzzleAdapter(new GuzzleClient());
$svc = new CrossborderService($config, $adapter);

$postingNumber = '39268230-0002-3';
$orderArr = $svc->get($postingNumber);
echo json_encode($orderArr);
```

```json
{
  "result": [
    {
      "address": {
        "address_tail": "г. Москва, ул. Центральная, 1",
        "addressee": "Петров Иван Владимирович",
        "city": "Москва",
        "comment": "",
        "country": "Россия",
        "district": "",
        "phone": "+7 495 123-45-67",
        "region": "Москва",
        "zip_code": "101000"
      },
      "auto_cancel_date": "2019-11-18T11:30:11.571Z",
      "cancel_reason_id": 76,
      "created_at": "2019-11-18T11:30:11.571Z",
      "customer_email": "petrov@email.com",
      "customer_id": 60006,
      "in_process_at": "2019-11-18T11:30:11.571Z",
      "order_id": 77712345,
      "order_nr": "1111444",
      "posting_number": "39268230-0002-3",
      "products": [
        {
          "name": "Фитнес-браслет",
          "offer_id": "DEP-1234",
          "price": "1900.00",
          "quantity": 1,
          "sku": 100056
        }
      ],
      "shipping_provider_id": 0,
      "status": "awaiting_approve",
      "tracking_number": ""
    }
  ]
}
```

## Products

### import

`/v1/product/import`

```php
use Gam6itko\OzonSeller\Service\V1\ProductsService;
use GuzzleHttp\Client as GuzzleClient;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

$config = [
    'clientId' => '<ozon seller client-id>',
    'apiKey' => '<ozon seller api-key>',
    // use prod host by default
];
$adapter = new GuzzleAdapter(new GuzzleClient());
$svcProduct = new ProductsService($config, $adapter);
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

$svcProduct->import($product);
// or
$svcProduct->import([$product, $product1, $product2, ...]);
// or
$res = $svcProduct->import(['items' => [$product, $product1, $product2, ...] ]);
echo $res['task_id']; // save it for checking by `importInfo`
```
