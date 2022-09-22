<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V2;

use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\TypeCaster;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

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
     * @param array $query [attribute_type, language]
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function attribute(int $categoryId, array $query = []): array
    {
        $query = ArrayHelper::pick($query, ['attribute_type', 'language']);
        $query = TypeCaster::castArr($query, [
            'attribute_type' => 'str',
            'language'       => 'str',
        ]);
        $query = array_merge([
            'category_id' => $categoryId,
            'language'    => 'RU',
        ], $query);

        return $this->request('POST', "{$this->path}/attribute", $query);
    }

    /**
     * Check the dictionary for attributes or options by theirs IDs.
     *
     * @param array $query [last_value_id, limit, language]
     *
     * @return array [result, has_next]
     */
    public function attributeValues(int $categoryId, int $attrId, array $query = []): array
    {
        $query = ArrayHelper::pick($query, ['last_value_id', 'limit', 'language']);
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

        return $this->request('POST', "{$this->path}/attribute/values", $query, true, false);
    }

    public function attributeValueByOption(string $language = 'RU', array $options = [])
    {
        $options = $this->ensureCollection($options);
        foreach ($options as &$o) {
            $o = ArrayHelper::pick($o, ['attribute_id', 'option_id']);
        }
        unset($o);

        $body = [
            'language' => $language,
            'options'  => $options,
        ];

        return $this->request('POST', "{$this->path}/attribute/value/by-option", $body);
    }

    /**
     * @see https://api-seller.ozon.ru/v2/category/tree
     */
    public function tree(?int $categoryId = null, string $language = 'DEFAULT')
    {
        $body = [
            'language' => $language,
        ];

        if ($categoryId) {
            assert($categoryId > 0);
            $body['category_id'] = $categoryId;
        }

        return $this->request('POST', "{$this->path}/tree", $body);
    }
}
