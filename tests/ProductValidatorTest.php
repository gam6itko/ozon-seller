<?php

namespace Gam6itko\OzonSeller\Tests;

use Gam6itko\OzonSeller\ProductValidator;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\ProductValidator
 * @group unit-test
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class ProductValidatorTest extends TestCase
{
    /**
     * @dataProvider dataValidCreate
     */
    public function testValidCreate(string $json)
    {
        $pv = new ProductValidator();
        $pv->validateItem(json_decode($json, true));
        self::assertTrue(true);
    }

    public function dataValidCreate()
    {
        return [
            ['{"offer_id":"1563","price":2590,"barcode":"8056225253563","description":"PRAGA – элегантное поло","name":"Футболка-поло PRAGA","vendor":"Errea","height":2,"depth":38,"width":29,"dimension_unit":"cm","weight":0.146,"weight_unit":"kg","category_id":15621031,"vat":0,"images":[{"file_name":"https://avatars1.githubusercontent.com/u/3841197?s=460&v=4","default":true}]}'],
        ];
    }

    /**
     * @dataProvider dataInvalid
     * @expectedException \Gam6itko\OzonSeller\Exception\ProductValidatorException
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
            ['{"offer_id":"1563","price":2590,"barcode":"8056225253563","description":"PRAGA – элегантное поло","name":"Футболка-поло PRAGA","vendor":"Errea","height":2,"depth":38,"width":29,"dimension_unit":"m","weight":0.146,"weight_unit":"ton","category_id":15621031,"vat":0,"images":[{"file_name":"https://avatars1.githubusercontent.com/u/3841197?s=460&v=4","default":true}]}'],
            ['{"offer_id":"1563","price":2590,"barcode":"8056225253563","description":"","name":"Футболка-поло PRAGA","vendor":"Errea","height":2,"depth":38,"width":29,"dimension_unit":"cm","weight":0.146,"weight_unit":"kg","category_id":15621031,"vat":0,"images":[{"file_name":"https://avatars1.githubusercontent.com/u/3841197?s=460&v=4","default":true}]}'],
        ];
    }

    /**
     * @dataProvider dataValidUpdate
     */
    public function testValidUpdate(string $json)
    {
        $pv = new ProductValidator('update');
        $pv->validateItem(json_decode($json, true));
        self::assertTrue(true);
    }

    /**
     * @return array
     */
    public function dataValidUpdate()
    {
        return [
            ['{"product_id":"123", "name": "name"}'],
        ];
    }

    /**
     * @dataProvider dataValidUpdate
     */
    public function testInvalidUpdate(string $json)
    {
        $pv = new ProductValidator('update');
        $pv->validateItem(json_decode($json, true));
        self::assertTrue(true);
    }

    /**
     * @return array
     */
    public function dataInvalidUpdate()
    {
        return [
            ['{"name": "name"}'],
        ];
    }

    /**
     * @dataProvider dataImages
     */
    public function testImages(string $json)
    {
        $pv = new ProductValidator('update');
        $array = $pv->validateItem(json_decode($json, true));
        self::assertNotEmpty($array);
        self::assertArrayHasKey('images', $array);
        self::assertCount(10, $array['images']);
    }

    /**
     * @return array
     */
    public function dataImages()
    {
        return [
            [
                '{
  "product_id": 15728170,
  "description": "description",
  "category_id": 35853052,
  "name": "name",
  "offer_id": "1925",
  "price": "2110",
  "vat": "0",
  "vendor": "MOTOROLA SOLUTIONS",
  "vendor_code": "B4P00811RDKMAW",
  "height": 18,
  "depth": 5,
  "width": 18,
  "dimension_unit": "cm",
  "weight": 500,
  "weight_unit": "g",
  "images": [
    {
      "file_name": "https://static.inexpost.ru/upl/d6/68/d668372c233a76de279c1a2a86f8fe4d.jpg",
      "default": true
    },
    {
      "file_name": "https://static.inexpost.ru/upl/82/64/8264aef53103a5df5e379d426973ecb9.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/fa/71/fa718a216db59b6639aa25b9d905dc62.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/6e/d2/6ed26575c447ac99cb1686108ba39d4e.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/92/12/921272e931a440a115066f56d119d094.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/25/03/25036013063beb2dcbcf7681a9aa9fa5.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/f6/75/f675e5d2ad5b82aa8c0cdcff18f370f5.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/42/fe/42fe7b4ab89776885445bb50948c54aa.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/26/ee/26eed0017f64ed0bf2ddcc53cde1d26a.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/27/10/27105293f16b80e39f7d9c87a958737b.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/18/6d/186dae6d872ec648f883604ca409a28a.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/f5/e6/f5e6b0319d5ebf690603e5d645140ab1.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/2e/17/2e17b23531f19b8a3cd11b7d15a89de2.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/1e/f3/1ef3b7eb9f9e4739dc46c8bb55d85290.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/c5/26/c526f9c7129bd87c8d01e88c1c5eb950.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/ea/f0/eaf087d25d5b1dc009d080533e31ce97.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/4b/fe/4bfed731f7a4c7dd8c8f9b65ee558be3.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/c5/88/c588b9484ffa2a4cfbfa3e08b8ee4e63.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/c1/23/c12316f25157675c9b2b51754ed75e7b.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/88/7a/887a0dfa4473670e158a5ce130542d7f.jpg",
      "default": false
    },
    {
      "file_name": "https://static.inexpost.ru/upl/41/d6/41d6c79fe0e5367e6127c363273fd3e5.jpg",
      "default": false
    }
  ]
}',
            ],
        ];
    }
}
