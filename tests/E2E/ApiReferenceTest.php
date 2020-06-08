<?php

namespace Gam6itko\OzonSeller\Tests\E2E;

use Gam6itko\OzonSeller\Service\V1\ActionsService;
use Gam6itko\OzonSeller\Service\V1\CategoriesService;
use Gam6itko\OzonSeller\Service\V1\ChatService;
use Gam6itko\OzonSeller\Service\V1\ProductsService;
use Gam6itko\OzonSeller\Service\V1\ReportService;
use Gam6itko\OzonSeller\Service\V2\CategoryService as V2CategoryService;
use Gam6itko\OzonSeller\Service\V2\Posting\CrossborderService;
use Gam6itko\OzonSeller\Service\V2\Posting\FboService;
use Gam6itko\OzonSeller\Service\V2\Posting\FbsService;
use Gam6itko\OzonSeller\Service\V2\ProductService as V2ProductService;
use PHPHtmlParser\Dom;
use PHPUnit\Framework\TestCase;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 *
 * @group  e2e
 */
class ApiReferenceTest extends TestCase
{
    const IGNORE_PREFIXES = [
        '/v1/order',
    ];

    const CONFIG = [
        CategoriesService::class  => [
            'prefix'  => '/v1/category',
            'mapping' => [
                'attribute' => 'attributes',
            ],
        ],
        ChatService::class        => ['prefix' => '/v1/chat'],
//        OrderService::class       => [
//            'prefix'  => '/v1/order',
//            'mapping' => [
//                '123456?translit=true'     => 'info',
//                'approve/crossborder'      => 'approve',
//                'cancel-reason/list'       => 'itemsCancelReasons',
//                'cancel/fbs'               => 'itemsCancelFbs',
//                'items/cancel/crossborder' => 'itemsCancelCrossboarder',
//                'shipping-provider/list'   => 'shippingProviders',
//            ],
//        ],
        ProductsService::class    => [
            'prefix'  => '/v1/product',
            'mapping' => [
                'prepayment/set' => 'setPrepayment',
            ],
        ],
        ReportService::class      => [
            'prefix'  => '/v1/report',
            'mapping' => [
                'products/create'     => 'products',
                'transactions/create' => 'transaction', //todo rename
            ],
        ],
        //Seller
        ActionsService::class     => [
            'prefix'  => '/v1/actions',
            'mapping' => [
                '' => 'list',
            ],
        ],
        // V2
        // Posting
        CrossborderService::class => [
            'prefix'  => '/v2/posting/crossborder',
            'mapping' => [
                'cancel-reason/list'     => 'cancelReasons',
                'shipping-provider/list' => 'shippingProviders',
            ],
        ],
        FboService::class         => [
            'prefix' => '/v2/posting/fbo',
        ],
        FbsService::class         => [
            'prefix'  => '/v2/posting/fbs',
            'mapping' => [
                'cancel-reason/list' => 'cancelReasons',
            ],
        ],
        V2CategoryService::class  => [
            'prefix' => '/v2/category',
        ],
        V2ProductService::class   => [
            'prefix' => '/v2/product',
        ],
    ];

    /**
     * Check for new api methods.
     */
    public function testActualRealization()
    {
        $dom = new Dom();
        $dom->loadFromUrl('https://cb-api.ozonru.me/apiref/en/');
        $tabs = $dom->find('.highlight.http.tab-http');
        self::assertNotEmpty($tabs->count());

        $theirMethods = [];
        /** @var Dom\HtmlNode $tab */
        foreach ($tabs as $tab) {
            $path = $tab->find('.nn')[0]->text;
            if ('/' === $path) {
                continue;
            }

            if (in_array($path, $theirMethods)) {
                continue;
            }
            $theirMethods[] = $path;
        }
        asort($theirMethods);
        $theirMethods = array_values($theirMethods);
        self::assertNotEmpty($theirMethods);

        foreach ($theirMethods as $path) {
            self::assertTrue($this->isRealized($path), "Method `$path` not realized");
        }
    }

    private function isRealized(string $path): bool
    {
        $path = preg_replace('/[^-|_|\/|0-9|a-z]/', '', $path);

        foreach (self::IGNORE_PREFIXES as $ignore) {
            if (0 === strpos($path, $ignore)) {
                return true;
            }
        }

        foreach (self::CONFIG as $class => $config) {
            if (0 !== $p = strpos($path, $config['prefix'])) {
                continue;
            }

            $method = ltrim(substr($path, strlen($config['prefix'])), '/');
            if (isset($config['mapping']) && array_key_exists($method, $config['mapping'])) {
                $method = $config['mapping'][$method];
            }

            // to camelCase
            $canonicalMethodName = lcfirst(implode('', array_map('ucfirst', explode('_', preg_replace('/\/|-|_/', '_', $method)))));

            return method_exists($class, $canonicalMethodName);
        }

        return false;
    }
}
