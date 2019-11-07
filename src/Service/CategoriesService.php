<?php

namespace Gam6itko\OzonSeller\Service;

use Gam6itko\OzonSeller\TypeCaster;

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

        return $this->request('POST', '/v1/category/tree', ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Receive the attributes list from the product page for a specified category.
     *
     * @see http://cb-api.ozonru.me/apiref/en/#t-title_get_categories_attributes
     *
     * @param int    $categoryId
     * @param string $language   [EN, RU]
     * @param array  $query
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function attributes(int $categoryId, string $language = 'RU', array $query = [])
    {
        $query = $this->faceControl($query, ['attribute_type']);
        $query = TypeCaster::castArr($query, ['attribute_type' => 'str']);
        $query = array_merge([
            'category_id' => $categoryId,
            'language'    => strtoupper($language),
        ], $query);

        return $this->request('POST', '/v1/category/attribute', ['body' => \GuzzleHttp\json_encode($query)]);
    }
}
