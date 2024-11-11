<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V4\Posting;

use Gam6itko\OzonSeller\Enum\PostingScheme;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;
use Gam6itko\OzonSeller\Utils\WithResolver;

/**
 * @psalm-type TShipResponseProduct = array{
 *     mandatory_mark: list<string>,
 *     name: string,
 *     offer_id: string,
 *     price: string,
 *     quantity: integer,
 *     sku: integer,
 *     currency_code: string
 * }
 * @psalm-type TShipResponseAdditionalData = array{
 *      posting_number: string,
 *      products: list<TShipResponseProduct>
 * }
 * @psalm-type TShipResponse = array{
 *      additional_data: TShipResponseAdditionalData,
 *      result: list<string>
 * }
 * @psalm-type THasProducts = array{
 *     products: list<TShipProduct>
 * }
 * @psalm-type TShipProduct = array{
 *     product_id: int,
 *     quantity: int
 * }
 * @psalm-type TShipPackageProduct = array{
 *      exemplarsIds: int,
 *      product_id: int
 *      quantity: int
 *  }
 *
 * @psalm-type TShipWith = array{additional_data: bool}
 */
class FbsService extends AbstractService
{
    private $path = '/v4/posting/fbs';

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/PostingAPI_ShipFbsPostingV4
     *
     * @param $packages list<THasProducts>
     * @param $with TShipWith
     *
     * @return TShipResponse
     */
    public function ship(array $packages, string $postingNumber, array $with = []): array
    {
        \assert([] !== $packages);
        \assert(!$this->isAssoc($packages));
        foreach ($packages as &$package) {
            \assert(\array_key_exists('products', $package));
            $package = ArrayHelper::pick($package, ['products']);
            \assert(!$this->isAssoc($package['products']));
            \assert(\count($package['products']) > 0);
        }

        $body = [
            'packages'       => $packages,
            'posting_number' => $postingNumber,
            'with' => WithResolver::resolve($with, 4, PostingScheme::FBS, __FUNCTION__),
        ];

        return $this->request('POST', "$this->path/ship", $body);
    }

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/PostingAPI_ShipFbsPostingPackage
     *
     * @return string PostingNumber
     */
    public function shipPackage(string $postingNumber, array $products): string
    {
        $body = [
            'posting_number' => $postingNumber,
            'products'       => $products,
        ];

        return $this->request('POST', "$this->path/ship/package", $body);
    }
}
