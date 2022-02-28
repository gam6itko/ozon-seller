<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\TypeCaster;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class CategoriesService extends AbstractService
{
    /**
     * Receive the list of all available item categories.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_categories_tree
     *
     * @param int    $categoryId
     * @param string $language   [EN, RU]
     *
     * @return array
     */
    public function tree(int $categoryId = null, string $language = 'RU')
    {
        $query = array_filter([
            'category_id' => $categoryId,
            'language'    => strtoupper($language),
        ]);

        return $this->request('POST', '/v1/category/tree', $query);
    }

    /**
     * Receive the attributes list from the product page for a specified category.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_categories_attributes
     *
     * @param string $language [EN, RU]
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function attributes(int $categoryId, string $language = 'RU', array $query = [])
    {
        $query = ArrayHelper::pick($query, ['attribute_type']);
        $query = TypeCaster::castArr($query, ['attribute_type' => 'str']);
        $query = array_merge([
            'category_id' => $categoryId,
            'language'    => strtoupper($language),
        ], $query);

        return $this->request('POST', '/v1/category/attribute', $query);
    }
}
