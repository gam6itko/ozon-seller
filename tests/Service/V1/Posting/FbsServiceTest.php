<?php

declare(strict_types=1);

namespace Gam6itko\OzonSeller\Tests\Service\V1\Posting;

use Gam6itko\OzonSeller\Service\V1\Posting\FbsService;
use Gam6itko\OzonSeller\Tests\Service\AbstractTestCase;
use Psr\Http\Client\ClientInterface;

/**
 * @coversDefaultClass \Gam6itko\OzonSeller\Service\V1\Posting\FbsService
 */
class FbsServiceTest extends AbstractTestCase
{
    protected function getClass(): string
    {
        return FbsService::class;
    }

    /**
     * @covers ::cancelReason
     */
    public function testCancelReason(): void
    {
        $this->quickTest(
            'cancelReason',
            [
                [
                    '12345678-0001-12',
                    '12345619-98741-12',
                ],
            ],
            [
                'POST',
                '/v1/posting/fbs/cancel-reason',
                '{"related_posting_numbers":["12345678-0001-12","12345619-98741-12"]}',
            ]
        );
    }

    /**
     * @covers ::cancelReason
     */
    public function testCancelReasonEmptyRequestException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $config = [123, 'api-key'];
        $client = $this->createMock(ClientInterface::class);
        $svc = new FbsService($config, $client, $this->createRequestFactory(), $this->createStreamFactory());
        $svc->cancelReason([]);
    }
}
