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
    public function attribute(int $categoryId, array $query = []): array
    {
        $query = $this->faceControl($query, ['attribute_type']);
        $query = TypeCaster::castArr($query, ['attribute_type' => 'str']);
        $query = array_merge([
            'category_id' => $categoryId,
            'language'    => 'RU',
        ], $query);

        return $this->request('POST', "{$this->path}/attribute", ['body' => \GuzzleHttp\json_encode($query)]);
    }

    /**
     * Check the dictionary for attributes or options by theirs IDs.
     *
     * @param array $query [last_value_id, limit, language]
     */
    public function attributeValues(int $categoryId, int $attrId, array $query = []): array
    {
        $query = $this->faceControl($query, ['last_value_id', 'limit', 'language']);
        $query = array_merge([
            'category_id'   => $categoryId,
            'attribute_id'  => $attrId,
            'limit'         => 1000,
            'last_value_id' => 0,
            'language'      => 'RU',
        ], $query);
        $query = TypeCaster::castArr($query, [
            'category_id'   => 'int',
            'attribute_id'  => 'int',
            'last_value_id' => 'int',
            'limit'         => 'int',
            'language'      => 'str',
        ]);

        return $this->request('POST', "{$this->path}/attribute/values", ['body' => \GuzzleHttp\json_encode($query)]);
    }

    public function attributeValueByOption(string $language = 'RU', array $options = [])
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

        return $this->request('POST', "{$this->path}/attribute/value/by-option", ['body' => \GuzzleHttp\json_encode($body)]);
    }
}
