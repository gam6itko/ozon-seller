<?php

namespace Gam6itko\OzonSeller\Service;

use Gam6itko\OzonSeller\TypeCaster;

class CategoriesService extends AbstractService
{
    /**
     * Receive the list of all available item categories.
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_get_categories_tree
     * @param int $categoryId
     * @return array
     */
    public function tree(int $categoryId = null)
    {
        return $this->request('GET', "/v1/categories/tree/{$categoryId}");
    }

    /**
     * Receive the attributes list from the product page for a specified category.
     * @see http://cb-api.test.ozon.ru/apiref/en/#t-title_get_categories_attributes
     * @param int $categoryId
     * @param array $query
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function attributes(int $categoryId, array $query = [])
    {
        $query = $this->faceControl($query, ['attribute_type']);
        $query = TypeCaster::castArr($query, ['attribute_type' => 'str']);

        return $this->request('GET', "/v1/categories/{$categoryId}/attributes", ['query' => $query]);
    }
}