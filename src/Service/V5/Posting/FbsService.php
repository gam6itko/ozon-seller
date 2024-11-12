<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V5\Posting;

use Gam6itko\OzonSeller\Service\AbstractService;


class FbsService extends AbstractService
{
    private $path = '/v5/fbs/posting';

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/PostingAPI_FbsPostingProductExemplarCreateOrGet
     */
    public function productExemplarCreateOrGet(string $postingNumber): array
    {
        $body = [
            'posting_number' => $postingNumber,
        ];
        return $this->request('POST', "{$this->path}/product/exemplar/create-or-get", $body);
    }
    
    /**
     * @see https://docs.ozon.ru/api/seller/#operation/PostingAPI_FbsPostingProductExemplarSet
     */
    public function productExemplarSet(int $multiBoxQty, string $postingNumber, array $products): bool
    {
        $body = [
            'multi_box_qty' => $multiBoxQty,
            'posting_number' => $postingNumber,
            'products' => $products,
        ];
        return $this->request('POST', "{$this->path}/product/exemplar/set", $body);
    }
}
