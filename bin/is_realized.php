<?php

declare(strict_types=1);

$whereIsAutoloader = [
    dirname(__DIR__).'/vendor/autoload.php',
    dirname(__DIR__).'/autoload.php',
];

foreach ($whereIsAutoloader as $filepath) {
    if (file_exists($filepath)) {
        require_once $filepath;
        break;
    }
}

use Gam6itko\OzonSeller\Service\V1\CategoriesService;
use Gam6itko\OzonSeller\Service\V1\ProductService as V1ProductService;
use Gam6itko\OzonSeller\Service\V2\Posting\CrossborderService;
use Gam6itko\OzonSeller\Service\V2\Posting\FbsService;
use Gam6itko\OzonSeller\Service\V2\ProductService as V2ProductService;
use Gam6itko\OzonSeller\Service\V2\ReturnsService as V2ReturnsService;
use Gam6itko\OzonSeller\Service\V3\ReturnsService as V3ReturnsService;
use Gam6itko\OzonSeller\Service\V4\ProductService as V4ProductService;
use GuzzleHttp\Client;

const MAPPING = [
    '/v1/categories/tree/{category_id}'              => null,
    '/v1/categories/{category_id}/attributes'        => null,
    '/v1/category/tree'                              => [CategoriesService::class, 'tree'],
    '/v1/category/attribute'                         => [CategoriesService::class, 'attributes'],
    '/v1/product/info/description'                   => null, //todo
    '/v1/product/list/price'                         => [V1ProductService::class, 'price'],
    '/v1/product/prepayment/set'                     => [V1ProductService::class, 'setPrepayment'],
    '/v1/products/info/{product_id}'                 => [V1ProductService::class, 'info'],
    '/v1/products/list'                              => null,
    '/v1/products/prices'                            => null,
    '/v1/products/stocks'                            => null,
    '/v1/products/update'                            => null,

    // V2
    '/v2/fbs/posting/delivered'                      => [FbsService::class.'delivered'],
    '/v2/fbs/posting/delivering'                     => [FbsService::class.'delivering'],
    '/v2/fbs/posting/last-mile'                      => [FbsService::class.'lastMile'],
    '/v2/fbs/posting/tracking-number/set'            => [FbsService::class.'setTrackingNumber'],
    '/v2/posting/crossborder/cancel-reason/list'     => [CrossborderService::class, 'cancelReasons'],
    '/v2/posting/crossborder/shipping-provider/list' => [CrossborderService::class, 'shippingProviders'],
    '/v2/posting/fbs/cancel-reason/list'             => [FbsService::class.'cancelReasons'],
    '/v2/products/info/attributes'                   => [V2ProductService::class, 'infoAttributes'],
    '/v2/returns/company/fbo'                        => [V2ReturnsService::class, 'company'],
    '/v2/returns/company/fbs'                        => [V2ReturnsService::class, 'company'],

    // V3 - TODO
    '/v3/returns/company/fbs'                        => [V3ReturnsService::class, 'company'],

    // V4
    '/v4/product/info/prices'                       => [V4ProductService::class, 'infoPrices'],
];

$client = new Client();
$response = $client->get('https://api-seller.ozon.ru/swagger.json');
$json = $response->getBody()->getContents();
$swagger = json_decode($json, true);

foreach ($swagger['paths'] as $path => $confArr) {
    if (array_key_exists($path, MAPPING)) {
        $classMethod = MAPPING[$path];
    } else {
        $classMethod = findMethod($path);
    }

    echo "$path: ";

    //mark as deprecated
    $conf = reset($confArr);
    if (!empty($conf['deprecated']) && isDeprecated($path)) {
        echo "\033[01;33mdeprecated \033[0m";
    }

    if (empty($classMethod)) {
        echo "\033[01;31mNotRealized\033[0m";
    } else {
        //show class::method
        echo "\033[01;32m".implode('::', $classMethod)."\033[0m";
    }

    echo PHP_EOL;
}

function isDeprecated(string $path): bool
{
    if (null === ($arr = findMethod($path))) {
        return true;
    }

    [$class, $method] = $arr;

    $refClass = new \ReflectionClass($class);
    $refMethod = $refClass->getMethod($method);
    if (!$docComment = $refMethod->getDocComment()) {
        return false;
    }

    return false !== strpos($docComment, '@deprecated');
}

/**
 * @return array|null
 */
function findMethod(string $path)
{
    $prefix = 'Gam6itko\\OzonSeller\\Service\\';
    $arr = array_map('ucfirst', array_filter(explode('/', $path)));
    do {
        $key = array_shift($arr);
        $class = $prefix.$key.'Service';
        if (class_exists($class)) {
            break;
        }

        $prefix .= $key.'\\';
    } while (!empty($arr));

    if (empty($arr)) {
        return null;
    }

    $arr = array_map(static function (string $string): string {
        return implode('', array_map('ucfirst', preg_split('/(_|-)/', $string)));
    }, $arr);
    $method = lcfirst(implode('', $arr));

    if (method_exists($class, $method)) {
        return [$class, $method];
    }

    return null;
}
