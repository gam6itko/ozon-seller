<?php
namespace Gam6itko\OzonSeller\Service;

class CategoriesService extends AbstractService
{
    /**
     * @param int $categoryId
     * @return array
     */
    public function tree(int $categoryId = null)
    {
        return $this->request('GET', "/v1/categories/tree/{$categoryId}");
    }

    /**
     * @param int $categoryId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function attributes(int $categoryId)
    {
        return $this->request('GET', "/v1/categories/{$categoryId}/attributes");
    }
}