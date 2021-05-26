<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V2;

use Gam6itko\OzonSeller\Enum\PostingScheme;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

class ReturnsService extends AbstractService
{
    private $path = '/v2/returns';

    /**
     * @param string $postingScheme Value from ['fbo', 'fbs']
     * @param array  $requestData   ['filter' => array, 'offset' => int, 'limit' => int]
     */
    public function company(string $postingScheme, array $requestData): array
    {
        $postingScheme = strtolower($postingScheme);
        if (!in_array($postingScheme, [PostingScheme::FBO, PostingScheme::FBS])) {
            throw new \LogicException("Unsupported posting scheme: $postingScheme");
        }

        $default = [
            'filter' => [],
            'offset' => 0,
            'limit'  => 10,
        ];

        $requestData = array_merge(
            $default,
            ArrayHelper::pick($requestData, array_keys($default))
        );

        return $this->request('POST', "{$this->path}/company/{$postingScheme}", $requestData);
    }
}
