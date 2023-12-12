<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V4\Posting;

use Gam6itko\OzonSeller\Enum\PostingScheme;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;
use Gam6itko\OzonSeller\Utils\WithResolver;

class FbsService extends AbstractService
{
    private $path = '/v4/posting/fbs';

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/PostingAPI_ShipFbsPostingV4
     *
     * @return array list of postings IDs
     */
    public function ship(array $packages, string $postingNumber, array $options = []): array
    {
        foreach ($packages as &$package) {
            $package = ArrayHelper::pick($package, ['products']);
        }

        $body = [
            'packages'       => $packages,
            'posting_number' => $postingNumber,
            'with'           => WithResolver::resolve($options, 3, PostingScheme::FBS, __FUNCTION__),
        ];

        return $this->request('POST', "$this->path/ship", $body);
    }
}
