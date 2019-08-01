<?php

use Gam6itko\OzonSeller\ProductValidator;
use PHPUnit\Framework\TestCase;

class ProductValidatorTest extends TestCase
{
    /**
     * @dataProvider dataValid
     * @param string $json
     */
    public function testValid(string $json)
    {
        $pv = new ProductValidator();
        $pv->validateItem(json_decode($json, true));
        self::assertTrue(true);
    }

    public function dataValid()
    {
        return [
            ['{"offer_id":"1563","price":2590,"barcode":"8056225253563","description":"PRAGA – элегантное поло","name":"Футболка-поло PRAGA","vendor":"Errea","height":2,"depth":38,"width":29,"dimension_unit":"cm","weight":0.146,"weight_unit":"kg","category_id":15621031,"vat":0,"images":[{"file_name":"https://avatars1.githubusercontent.com/u/3841197?s=460&v=4","default":true}]}']
        ];
    }

    /**
     * @dataProvider dataInvalid
     * @expectedException \LogicException
     * @param string $json
     */
    public function testInvalid(string $json)
    {
        $pv = new ProductValidator();
        $pv->validateItem(json_decode($json, true));
        self::assertTrue(true);
    }

    public function dataInvalid()
    {
        return [
            ['{"offer_id":"1563"}'],
            ['{"offer_id":"1563","price":2590,"barcode":"8056225253563","description":"PRAGA – элегантное поло","name":"Футболка-поло PRAGA","vendor":"Errea","height":2,"depth":38,"width":29,"dimension_unit":"m","weight":0.146,"weight_unit":"ton","category_id":15621031,"vat":0,"images":[{"file_name":"https://avatars1.githubusercontent.com/u/3841197?s=460&v=4","default":true}]}']
        ];
    }
}