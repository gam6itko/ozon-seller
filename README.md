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