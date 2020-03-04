<?php

namespace Gam6itko\OzonSeller\Service\V2;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\TypeCaster;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class CategoryService extends AbstractService
{
    private $path = '/v2/category';

    /**
     * Receive the attributes list from the product page for a specified category.
     *
     * @see https://cb-api.ozonru.me/apiref/en/#t-title_category_attribute
     *
     * @param string $language [EN, RU]
     * @param array  $query    [attribute_type]
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function attribute(int $categoryId, string $language = 'RU', array $query = []): array
    {
        $query = $this->faceControl($query, ['attribute_type']);
        $query = TypeCaster::castArr($query, ['attribute_type' => 'str']);
        $query = array_merge([
            'category_id' => $categoryId,
            'language'    => strtoupper($language),
        ], $query);

        return $this->request('POST', "{$this->path}/attribute", ['body' => \GuzzleHttp\json_encode($query)]);
    }

    public function attribute​ValueByOption(string $language = 'RU', array $options = [])
    {
        $options = $this->ensureCollection($options);
        foreach ($options as &$o) {
            $o = $this->faceControl($o, ['attribute_id', 'option_id']);
        }
        unset($o);

        $body = [
            'language' => $language,
            'options'  => $options,
        ];

        return $this->request('POST', "{$this->path}/attribute​/value​/by-option", ['body' => \GuzzleHttp\json_encode($body)]);
    }
}
