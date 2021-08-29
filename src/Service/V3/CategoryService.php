<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V3;

use Gam6itko\OzonSeller\Enum\Language;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\TypeCaster;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

class CategoryService extends AbstractService
{
    private $path = '/v3/category';

    /**
     * @param array|int $categoryId
     * @param array     $query      [attribute_type, language]
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function attribute($categoryId, array $query = []): array
    {
        $query = ArrayHelper::pick($query, ['attribute_type', 'language']);
        $query = TypeCaster::castArr($query, [
            'attribute_type' => 'str',
            'language'       => 'str',
        ]);
        $query = array_merge([
            'category_id' => (array) $categoryId,
            'language'    => Language::DEFAULT,
        ], $query);

        return $this->request('POST', "{$this->path}/attribute", $query);
    }
}
