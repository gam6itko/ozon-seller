# Changelog

# 0.5.0

- Классы сервисов, которые имеют метод `list` (CrossborderService, FboService, FbsService) являются реализациями
  интерфейса `HasOrdersInterface`.

## breaking changes

У методов перечисленных ниже изменилась сигнатура. Метод `list` в качестве аргумента принимает только массив.

- Gam6itko\OzonSeller\Service\V2\Posting\CrossborderService::list
- Gam6itko\OzonSeller\Service\V2\Posting\FboService::list
- Gam6itko\OzonSeller\Service\V2\Posting\FbsService::list

### before v0.5

```php
use Gam6itko\OzonSeller\Service\V2\Posting\CrossborderService;

$svc = new CrossborderService($config, $client);
$svc->list(
    SortDirection::ASC, 
    0, 
    10, 
    [
        'since'  => new \DateTime('2019-01-01'),
        'to'     => new \DateTime('2020-01-01'),
        'status' => Status::AWAITING_APPROVE,
    ]
);
```

### after v0.5

```php
use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Enum\Status;
use Gam6itko\OzonSeller\Service\V2\Posting\CrossborderService;

$svc = new CrossborderService($config, $client);
$svc->list([
    'dir' => SortDirection::ASC, 
    'offset' => 0, 
    'limit' => 10, 
    'filter' => [
        'since'  => new \DateTime('2019-01-01'),
        'to'     => new \DateTime('2020-01-01'),
        'status' => Status::AWAITING_APPROVE,
    ]
]);

// or

$svc->list([
    'filter' => [
        'since'  => new \DateTime('2019-01-01'),
        'to'     => new \DateTime('2020-01-01'),
        'status' => Status::AWAITING_APPROVE,
    ]
]);
```
Значения по-умолчанию:

```yaml
dir: 'asc'
offset: 0
limit: 10
```


# 0.4.1

- ProductValidator для экспорта товаров V2. При создании экземпляра объекта необходимо передать вторым аргументом версию
  API.
- CategoryService::attributeValues возвращает массив содержащий ключи [result, has_next].

# 0.4.0

- При создании экземпляра Service-класса необходимо передать объект класса `Psr\Http\Client\ClientInterface`.

# 0.3.0

- Удалены `deprecated` методы.
- declare(strict_types=1);
- Поддержка сервисов API-V2.
- # 17
- # 18

# 0.2.4

- Удален класс `OrderService` т.к. сервер больше не поддерживает запросы `/v1/order`
- ProductsService.updateStocks учитывает параметр `offer_id`
- ProductsService.updatePrices добавлено приведение типов аргументов перед отправкой.
- ProductsService.import приведение типов аргументов.

# 0.2.3

- [change] FboService, FbsService метод `list` изменилась последовательность параметров. Добавлена поддержка параметра
  filter.status

# 0.2.2

- классы работы с API унаследованные от `AbstractService` поддерживают логирование запросов и ответов от сервера Ozon.
  Необходимо передать объект LoggerInterface в метод setLogger().

# 0.2.1

- [fix] CrossborderService методы `approve` и `cancel` всегда возвращали `false`

# 0.2.0

- Поддержка новый методов API `/v2/posting`
- ProductService. Удалена функция `create`, вместо него используйте `import`.
- DeliverySchema::CROSSBOARDER исправлена опечатка на DeliverySchema::CROSSBORDER