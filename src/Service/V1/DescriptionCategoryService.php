<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service\V1;

use Gam6itko\OzonSeller\Enum\Language;
use Gam6itko\OzonSeller\Service\AbstractService;
use Gam6itko\OzonSeller\TypeCaster;
use Gam6itko\OzonSeller\Utils\ArrayHelper;

/**
 * @psalm-type TLanguage = Language::*
 * @psalm-type TCategoryNodeChildSameAsParent = array
 * @psalm-type TCategoryNode = array{
 *     description_category_id: positive-int,
 *     category_name: string,
 *     disabled: bool,
 *     children: list<TCategoryNodeChildSameAsParent>,
 *     type_id?: positive-int,
 *     type_name?: string
 * }
 * @psalm-type TAttribute = array{
 *     id: positive-int,
 *     attribute_complex_id: int,
 *     name: string,
 *     description: string,
 *     type: string,
 *     is_collection: bool,
 *     is_required: bool,
 *     is_aspect: bool,
 *     max_value_count: int,
 *     group_name: string,
 *     group_id: int,
 *     dictionary_id: int,
 *     category_dependent: bool,
 *     complex_is_collection: bool
 * }
 * @psalm-type TAttributeValue = array{
 *     id: positive-int,
 *     value: string,
 *     info: string,
 *     picture: string
 * }
 * @psalm-type TQuery = array{language?: TLanguage}
 * @psalm-type TAttributeValuesQuery = array{
 *     language?: TLanguage,
 *     last_value_id?: positive-int,
 *     limit?: positive-int
 * }
 * @psalm-type TAttributeValuesSearchQuery = array{
 *     value: non-empty-string,
 *     limit?: positive-int,
 *     language?: TLanguage
 * }
 * @psalm-type TTreeResponse = array{result: list<TCategoryNode>}
 * @psalm-type TAttributesResponse = array{result: list<TAttribute>}
 * @psalm-type TAttributeValuesResponse = array{
 *     result: list<TAttributeValue>,
 *     has_next: bool
 * }
 */
class DescriptionCategoryService extends AbstractService
{
    protected const DEFAULT_ATTRIBUTE_VALUES_LIMIT = 2000;
    protected const DEFAULT_ATTRIBUTE_VALUES_SEARCH_LIMIT = 100;

    private $path = '/v1/description-category';

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/DescriptionCategoryAPI_GetTree
     *
     * @param TQuery $query
     * @return TTreeResponse
     */
    public function getCategoryTree(array $query = [])
    {
        $query = ArrayHelper::pick($query, ['language']);

        $query = array_merge(
            [
                'language' => Language::DEFAULT,
            ],
            $query
        );

        $query = TypeCaster::castArr(
            $query,
            [
                'language' => 'str',
            ]
        );

        return $this->request('POST', "{$this->path}/tree", $query);
    }

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/DescriptionCategoryAPI_GetAttributes
     *
     * @param positive-int $categoryId
     * @param positive-int $typeId
     * @param TQuery $query
     * @return TAttributesResponse
     */
    public function getCategoryAttributes(int $categoryId, int $typeId, array $query = [])
    {
        $query = ArrayHelper::pick($query, ['language']);

        $query = array_merge(
            [
                'description_category_id' => $categoryId,
                'type_id' => $typeId,
                'language' => Language::DEFAULT,
            ],
            $query
        );

        $query = TypeCaster::castArr(
            $query,
            [
                'description_category_id' => 'int',
                'type_id' => 'int',
                'language' => 'str',
            ]
        );

        return $this->request('POST', "{$this->path}/attribute", $query);
    }

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/DescriptionCategoryAPI_GetAttributeValues
     *
     * @param positive-int $categoryId
     * @param positive-int $typeId
     * @param positive-int $attributeId
     * @param TAttributeValuesQuery $query
     * @return TAttributeValuesResponse
     */
    public function getAttributeValues(int $categoryId, int $typeId, int $attributeId, array $query = [])
    {
        $query = ArrayHelper::pick($query, ['language', 'last_value_id', 'limit']);

        $query = array_merge(
            [
                'description_category_id' => $categoryId,
                'type_id' => $typeId,
                'attribute_id' => $attributeId,
                'limit' => self::DEFAULT_ATTRIBUTE_VALUES_LIMIT,
                'language' => Language::DEFAULT,
            ],
            $query
        );

        $query = TypeCaster::castArr(
            $query,
            [
                'description_category_id' => 'int',
                'type_id' => 'int',
                'attribute_id' => 'int',
                'limit' => 'int',
                'last_value_id' => 'int',
                'language' => 'str',
            ]
        );

        return $this->request('POST', "{$this->path}/attribute/values", $query, true, false);
    }

    /**
     * @see https://docs.ozon.ru/api/seller/#operation/DescriptionCategoryAPI_SearchAttributeValues
     *
     * @param positive-int $categoryId
     * @param positive-int $typeId
     * @param positive-int $attributeId
     * @param TAttributeValuesSearchQuery $query
     * @return TAttributeValuesResponse
     */
    public function searchAttributeValues(int $categoryId, int $typeId, int $attributeId, array $query)
    {
        $query = ArrayHelper::pick($query, ['value', 'limit']);

        $query = array_merge(
            [
                'description_category_id' => $categoryId,
                'type_id' => $typeId,
                'attribute_id' => $attributeId,
                'limit' => self::DEFAULT_ATTRIBUTE_VALUES_SEARCH_LIMIT,
            ],
            $query
        );

        $query = TypeCaster::castArr(
            $query,
            [
                'description_category_id' => 'int',
                'type_id' => 'int',
                'attribute_id' => 'int',
                'limit' => 'int',
                'value' => 'str',
            ]
        );

        return $this->request('POST', "{$this->path}/attribute/values/search", $query, true, false);
    }
}
