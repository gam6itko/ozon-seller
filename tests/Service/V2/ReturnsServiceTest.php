<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V2;

use Gam6itko\OzonSeller\Enum\PostingScheme;
use Gam6itko\OzonSeller\Service\V2\ReturnsService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;
use Psr\Http\Client\ClientInterface;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V2\ReturnsService
 */
class ReturnsServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return ReturnsService::class;
    }

    /**
     * @covers ::company
     */
    public function testCompany(): void
    {
        $this->quickTest(
            'company',
            [
                PostingScheme::FBO,
                ['filter' => ['posting_number' => '00000000-0000-0']],
            ],
            [
                'POST',
                '/v2/returns/company/fbo',
                '{"filter":{"posting_number":"00000000-0000-0"},"offset":0,"limit":10}',
            ]
        );
    }

    /**
     * @covers ::company
     * @dataProvider dataCompanyWrongScheme
     */
    public function testCompanyWrongScheme(string $postingScheme): void
    {
        self::expectExceptionMessage("Unsupported posting scheme: $postingScheme");

        $client = $this->createMock(ClientInterface::class);
        $svc = new ReturnsService(['clientId' => 1, 'apiKey' => '123'], $client, $this->createRequestFactory(), $this->createStreamFactory());
        $svc->company($postingScheme, []);
    }

    public function dataCompanyWrongScheme(): iterable
    {
        yield [PostingScheme::CROSSBORDER];

        yield ['boom'];
    }
}
