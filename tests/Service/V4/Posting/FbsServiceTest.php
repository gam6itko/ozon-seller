<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V4\Posting;

use Gam6itko\OzonSeller\Service\V4\Posting\FbsService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V4\Posting\FbsService
 */
final class FbsServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return FbsService::class;
    }

    /**
     * @covers ::ship
     *
     * @dataProvider dataShipValid
     */
    public function testShipValid(array $arguments, string $expectedJson): void
    {
        $this->quickTest(
            'ship',
            $arguments,
            [
                'POST',
                '/v4/posting/fbs/ship',
                $expectedJson,
            ]
        );
    }

    public function dataShipValid(): iterable
    {
        yield [
            [
                // packages
                [
                    [
                        'products' => [
                            [
                                'product_id' => 185479045,
                                'quantity'   => 1,
                            ],
                        ],
                    ],
                ],
                // posting_number
                '89491381-0072-1',
                // with
                ['additional_data' => true],
            ],
            '{"packages":[{"products":[{"product_id":185479045,"quantity":1}]}],"posting_number":"89491381-0072-1","with":{"additional_data":true}}',
        ];

        yield [
            [
                // packages
                [
                    [
                        'products' => [
                            [
                                'product_id' => 185479045,
                                'quantity'   => 1,
                            ],
                            [
                                'product_id' => 185479111,
                                'quantity'   => 2,
                            ],
                        ],
                    ],
                    [
                        'products' => [
                            [
                                'product_id' => 222222222,
                                'quantity'   => 3,
                            ],
                            [
                                'product_id' => 444444444,
                                'quantity'   => 4,
                            ],
                        ],
                    ],
                ],
                // posting_number
                '89491382-0073-1',
            ],
            '{"packages":[{"products":[{"product_id":185479045,"quantity":1},{"product_id":185479111,"quantity":2}]},{"products":[{"product_id":222222222,"quantity":3},{"product_id":444444444,"quantity":4}]}],"posting_number":"89491382-0073-1","with":{"additional_data":false}}',
        ];
    }

    /**
     * @covers ::ship
     *
     * @dataProvider dataShipInvalidPayload
     */
    public function testShipInvalidPayload(array $packages, string $postingNumber, array $with = []): void
    {
        self::expectException(\AssertionError::class);
        $svc = new FbsService(
            [123, 'api-key', 'https://packagist.org/'],
            $this->createMock(ClientInterface::class),
            $this->createMock(RequestFactoryInterface::class),
            $this->createMock(StreamFactoryInterface::class)
        );
        $svc->ship($packages, $postingNumber);
    }

    public function dataShipInvalidPayload(): iterable
    {
        yield 'empty packages array' => [
            [],
            '89491381-0072-1',
        ];

        yield 'packages is not list' => [
            [
                'products' => [
                    [
                        'product_id' => 185479045,
                        'quantity'   => 1,
                    ],
                ],
            ],
            '89491381-0072-1',
        ];

        yield 'products is not list' => [
            [
                [
                    'products' => [
                        'product_id' => 185479045,
                        'quantity'   => 1,
                    ],
                ],
            ],
            '89491381-0072-1',
        ];

        yield 'empty products' => [
            [
                [
                    'products' => [],
                ],
            ],
            '89491381-0072-1',
        ];
    }
}
