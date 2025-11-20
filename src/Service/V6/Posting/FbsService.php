<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V6\Posting;

use Gam6itko\OzonSeller\Service\AbstractService;

/**
 * @psalm-type TMark = array{
 *     mark: string,
 *     mark_type: string
 * }
 * @psalm-type TExemplarData = array{
 *     exemplar_id: int,
 *     gtd: string,
 *     is_gtd_absent: bool,
 *     is_rnpt_absent: bool,
 *     marks: TMark[],
 *     rnpt: string,
 *     weight: float
 * }
 * @psalm-type TProductRequestData = array{
 *     exemplars: TExemplarData[],
 *     product_id: int
 * }
 * @psalm-type TProductResponseData = array{
 *     exemplars: TExemplarData[],
 *     product_id: int,
 *     has_imei: bool,
 *     is_gtd_needed: bool,
 *     is_jw_uin_needed: bool,
 *     is_mandatory_mark_needed: bool,
 *     is_mandatory_mark_possible: bool,
 *     is_rnpt_needed: bool,
 *     quantity: int,
 *     is_weight_needed: bool,
 *     weight_max: float,
 *     weight_min: float
 * }
 * @psalm-type TProductsExemplarGetResponseData = array{
 *     multi_box_qty: int,
 *     posting_number: string,
 *     products: TProductResponseData[]
 * }
 * @psalm-type TResponseDetails = array{
 *     typeUrl: string,
 *     value: string
 * }
 * @psalm-type TExemplarSetResponseData = array{
 *     code: int,
 *     details: TResponseDetails[],
 *     message: string
 * }
 */
class FbsService extends AbstractService
{
    private $path = '/v6/fbs/posting';

    /**
     * @see https://docs.ozon.ru/api/seller/?abt_att=1#operation/PostingAPI_FbsPostingProductExemplarCreateOrGetV6
     *
     * @return TProductsExemplarGetResponseData
     */
    public function productExemplarCreateOrGet(string $postingNumber): array
    {
        $body = [
            'posting_number' => $postingNumber,
        ];

        return $this->request('POST', "{$this->path}/product/exemplar/create-or-get", $body);
    }

    /**
     * @see https://docs.ozon.ru/api/seller/?abt_att=1#operation/PostingAPI_FbsPostingProductExemplarSetV6
     *
     * @param int                   $multiBoxQty   Quantity of boxes the product is packed in
     * @param string                $postingNumber Shipment number
     * @param TProductRequestData[] $products      Product list
     *
     * @return TExemplarSetResponseData
     */
    public function productExemplarSet(int $multiBoxQty, string $postingNumber, array $products): array
    {
        $body = [
            'multi_box_qty'  => $multiBoxQty,
            'posting_number' => $postingNumber,
            'products'       => $products,
        ];

        return $this->request('POST', "{$this->path}/product/exemplar/set", $body);
    }
}
