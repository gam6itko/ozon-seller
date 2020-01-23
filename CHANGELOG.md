# Changelog

# 0.2.4
- ProductsService.updateStocks учитывает параметр `offer_id`
- ProductsService.updatePrices добавлено приведение типов аргументов перед отправкой.
- ProductsService.import приведение типов аргументов.
 

# 0.2.3
- [change] FboService, FbsService метод `list` изменилась последовательность параметров. Добавлена поддержка параметра filter.status 

# 0.2.2
- классы работы с API унаследованные от `AbstractService` поддерживают логирование запросов и ответов от сервера Ozon. 
    Необходимо передать объект LoggerInterface в метод setLogger().

# 0.2.1
- [fix] CrossborderService методы `approve` и `cancel` всегда возвращали `false`

# 0.2.0
- Поддержка новый методов API `/v2/posting`
- ProductService. Удалена функция `create`, вместо него используйте `import`. 
- DeliverySchema::CROSSBOARDER исправлена опечатка на DeliverySchema::CROSSBORDER