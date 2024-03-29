<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests;

use Gam6itko\OzonSeller\Exception\ProductValidatorException;
use Gam6itko\OzonSeller\ProductValidator;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\ProductValidator
 *
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
class ProductValidatorTest extends TestCase
{
    /**
     * @dataProvider dataValidCreate
     */
    public function testValidCreate(string $json): void
    {
        $pv = new ProductValidator();
        $pv->validateItem(json_decode($json, true));
        self::assertTrue(true);
    }

    public function dataValidCreate(): iterable
    {
        yield ['{"offer_id":"1563","price":2590,"barcode":"8056225253563","description":"PRAGA – элегантное поло","name":"Футболка-поло PRAGA","vendor":"Errea","height":2,"depth":38,"width":29,"dimension_unit":"cm","weight":0.146,"weight_unit":"kg","category_id":15621031,"vat":0,"images":[{"file_name":"https://avatars1.githubusercontent.com/u/3841197?s=460&v=4","default":true}]}'];
    }

    /**
     * @dataProvider dataInvalid
     */
    public function testInvalid(string $json): void
    {
        $this->expectException(ProductValidatorException::class);

        $pv = new ProductValidator();
        $pv->validateItem(json_decode($json, true));
        self::assertTrue(true);
    }

    public function dataInvalid(): iterable
    {
        yield ['{"offer_id":"1563"}'];
        yield ['{"offer_id":"1563","price":2590,"barcode":"8056225253563","description":"PRAGA – элегантное поло","name":"Футболка-поло PRAGA","vendor":"Errea","height":2,"depth":38,"width":29,"dimension_unit":"m","weight":0.146,"weight_unit":"ton","category_id":15621031,"vat":0,"images":[{"file_name":"https://avatars1.githubusercontent.com/u/3841197?s=460&v=4","default":true}]}'];
        yield ['{"offer_id":"1563","price":2590,"barcode":"8056225253563","description":"","name":"Футболка-поло PRAGA","vendor":"Errea","height":2,"depth":38,"width":29,"dimension_unit":"cm","weight":0.146,"weight_unit":"kg","category_id":15621031,"vat":0,"images":[{"file_name":"https://avatars1.githubusercontent.com/u/3841197?s=460&v=4","default":true}]}'];
    }

    /**
     * @dataProvider dataValidUpdate
     */
    public function testValidUpdate(string $json): void
    {
        $pv = new ProductValidator('update');
        $pv->validateItem(json_decode($json, true));
        self::assertTrue(true);
    }

    public function dataValidUpdate(): iterable
    {
        yield ['{"product_id":"123", "name": "name"}'];
    }

    /**
     * @dataProvider dataValidUpdate
     */
    public function testInvalidUpdate(string $json): void
    {
        $pv = new ProductValidator('update');
        $pv->validateItem(json_decode($json, true));
        self::assertTrue(true);
    }

    public function dataInvalidUpdate(): iterable
    {
        yield ['{"name": "name"}'];
    }

    /**
     * @dataProvider dataImages
     */
    public function testImages(string $json): void
    {
        $pv = new ProductValidator('update');
        $array = $pv->validateItem(json_decode($json, true));
        self::assertNotEmpty($array);
        self::assertArrayHasKey('images', $array);
        self::assertCount(10, $array['images']);
    }

    public function dataImages(): iterable
    {
        yield [
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
      "file_name": "https://static.dimain.com/upl/d6/68/d668372c233a76de279c1a2a86f8fe4d.jpg",
      "default": true
    },
    {
      "file_name": "https://static.dimain.com/upl/82/64/8264aef53103a5df5e379d426973ecb9.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/fa/71/fa718a216db59b6639aa25b9d905dc62.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/6e/d2/6ed26575c447ac99cb1686108ba39d4e.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/92/12/921272e931a440a115066f56d119d094.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/25/03/25036013063beb2dcbcf7681a9aa9fa5.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/f6/75/f675e5d2ad5b82aa8c0cdcff18f370f5.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/42/fe/42fe7b4ab89776885445bb50948c54aa.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/26/ee/26eed0017f64ed0bf2ddcc53cde1d26a.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/27/10/27105293f16b80e39f7d9c87a958737b.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/18/6d/186dae6d872ec648f883604ca409a28a.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/f5/e6/f5e6b0319d5ebf690603e5d645140ab1.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/2e/17/2e17b23531f19b8a3cd11b7d15a89de2.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/1e/f3/1ef3b7eb9f9e4739dc46c8bb55d85290.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/c5/26/c526f9c7129bd87c8d01e88c1c5eb950.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/ea/f0/eaf087d25d5b1dc009d080533e31ce97.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/4b/fe/4bfed731f7a4c7dd8c8f9b65ee558be3.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/c5/88/c588b9484ffa2a4cfbfa3e08b8ee4e63.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/c1/23/c12316f25157675c9b2b51754ed75e7b.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/88/7a/887a0dfa4473670e158a5ce130542d7f.jpg",
      "default": false
    },
    {
      "file_name": "https://static.dimain.com/upl/41/d6/41d6c79fe0e5367e6127c363273fd3e5.jpg",
      "default": false
    }
  ]
}',
        ];
    }

    public function testFail(): void
    {
        $this->expectException(\LogicException::class);

        $pv = new ProductValidator('boom');
        $pv->validateItem([]);
    }

    /**
     * @dataProvider dataV2
     */
    public function testV2(array $item, array $expected): void
    {
        $pv = new ProductValidator('create', 2);
        self::assertSame($expected, $pv->validateItem($item));
    }

    public function dataV2(): iterable
    {
        yield [
            [
                'foo' => 1,
                'bar' => true,
                'baz' => ['you', 'shall', 'not', 'pass'],

                'product_id'  => 123,
                'description' => 'Description for item',
                'name'        => 'Наушники Apple AirPods 2 (без беспроводной зарядки чехла)',
                'vendor_code' => 'AM016209',
                'quantity'    => '3',

                'offer_id'    => '16209',
                'category_id' => '17036198',
                'price'       => 10110,
                'vat'         => 0,
                'barcode'     => null,

                'height'         => '55',
                'depth'          => '22',
                'width'          => '45',
                'dimension_unit' => 'mm',

                'weight'      => '8',
                'weight_unit' => 'g',

                'image_group_id' => null,

                'images'     => [
                    [
                        'file_name' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MRXJ2?wid=1144&hei=1144&fmt=jpeg&qlt=95&op_usm=0.5,0.5&.v=1551489675083',
                        'default'   => true,
                    ],
                ],
                'attributes' => [
                    [
                        'id'    => 8229,
                        'value' => '193',
                    ],
                ],
            ],

            [
                'name'        => 'Наушники Apple AirPods 2 (без беспроводной зарядки чехла)',
                'offer_id'    => '16209',
                'category_id' => 17036198,
                'price'       => '10110',
                'vat'         => '0',
                'barcode'     => null,

                'height'         => 55,
                'depth'          => 22,
                'width'          => 45,
                'dimension_unit' => 'mm',

                'weight'      => 8,
                'weight_unit' => 'g',

                'image_group_id' => null,

                'images'     => [
                    [
                        'file_name' => 'https://store.storeimages.cdn-apple.com/4982/as-images.apple.com/is/MRXJ2?wid=1144&hei=1144&fmt=jpeg&qlt=95&op_usm=0.5,0.5&.v=1551489675083',
                        'default'   => true,
                    ],
                ],
                'attributes' => [
                    [
                        'id'    => 8229,
                        'value' => '193',
                    ],
                ],
            ],
        ];
    }
}
