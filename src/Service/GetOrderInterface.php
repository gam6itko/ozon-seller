<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service;

interface GetOrderInterface
{
    public function get(string $postingNumber, array $options = []): array;
}
