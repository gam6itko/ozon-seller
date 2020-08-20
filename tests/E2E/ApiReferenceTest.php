<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\E2E;

use Gam6itko\OzonSeller\Service\V1\CategoriesService;
use Gam6itko\OzonSeller\Service\V1\ProductService as V1ProductService;
use Gam6itko\OzonSeller\Service\V2\Posting\CrossborderService;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 *
 * @group  e2e
 */
class ApiReferenceTest extends TestCase
{
//    const IGNORE_PREFIXES = [
//        '/v1/order',
//    ];

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
        '/v2/posting/crossborder/cancel-reason/list'     => [CrossborderService::class, 'cancelReasons'],
        '/v2/posting/crossborder/shipping-provider/list' => [CrossborderService::class, 'shippingProviders'],
    ];

    /**
     * Check for new api methods.
     */
    public function testActualRealization(): void
    {
        $client = new Client();
        $response = $client->get('https://api-seller.ozon.ru/swagger.json');
        $json = $response->getBody()->getContents();
        $swagger = json_decode($json, true);

        foreach ($swagger['paths'] as $path => $confArr) {
            if (array_key_exists($path, self::MAPPING)) {
                continue;
            }

            $conf = reset($confArr);
            if (!empty($conf['deprecated'])) {
                self::assertTrue($this->isDeprecated($path), 'You should delete or mark method as deprecated. '.$path);
                continue;
            }

//            if (!$this->isRealized($path)) {
//                echo "Method `$path` not realized".PHP_EOL;
//            }
            self::assertTrue($this->isRealized($path), "Method `$path` not realized");
        }
    }

    private function isDeprecated(string $path): bool
    {
        if (null === ($arr = $this->findMethod($path))) {
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

    private function isRealized(string $path): bool
    {
//        $path = preg_replace('/[^-|{|}|_|\/|0-9|a-z]/', '', $path);

//        if (array_key_exists($path, self::MAPPING)) {
//            return true;
//        }

        if (null !== $this->findMethod($path)) {
            return true;
        }

        return false;
    }

    /**
     * @return array|null
     */
    private function findMethod(string $path)
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
}
