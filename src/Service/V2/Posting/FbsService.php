<?php

namespace Gam6itko\OzonSeller\Service\V2\Posting;

use Gam6itko\OzonSeller\Enum\SortDirection;
use Gam6itko\OzonSeller\Enum\Status;
use Gam6itko\OzonSeller\Service\AbstractService;
use GuzzleHttp\Exception\BadResponseException;

class FbsService extends AbstractService
{
    private $path = '/v2/posting/fbs';

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_list
     *
     * @param array $filter [since, to, status]
     */
    public function list(string $sort = SortDirection::ASC, int $offset = 0, int $limit = 10, array $filter = []): array
    {
        $filter = $this->faceControl($filter, ['since', 'to', 'status']);
        foreach (['since', 'to'] as $key) {
            if (isset($filter[$key]) && $filter[$key] instanceof \DateTimeInterface) {
                $filter[$key] = $filter[$key]->format(DATE_RFC3339);
            }
        }

        $body = [
            'filter' => $filter,
            'dir'    => $sort,
            'offset' => $offset,
            'limit'  => $limit,
        ];

        return $this->request('POST', "{$this->path}/list", ['body' => \GuzzleHttp\json_encode($body)]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_get
     */
    public function get(string $postingNumber): array
    {
        return $this->request('POST', "{$this->path}/get", ['body' => \GuzzleHttp\json_encode(['posting_number' => $postingNumber])]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_unfulfilled_list
     *
     * @return array|string
     */
    public function unfulfilledList($status, string $sort = SortDirection::ASC, int $offset = 0, int $limit = 10): array
    {
        if (is_string($status)) {
            $status = [$status];
        }
        foreach ($status as $s) {
            if (!in_array($s, Status::getList())) {
                throw new \LogicException("Incorrect status `$s`");
            }
        }

        $body = [
            'status' => $status,
            'dir'    => $sort,
            'offset' => $offset,
            'limit'  => $limit,
        ];

        return $this->request('POST', "{$this->path}/unfulfilled/list", ['body' => \GuzzleHttp\json_encode($body)]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_ship
     *
     * @return string list of postings IDs
     */
    public function ship(array $packages, string $postingNumber): string
    {
        foreach ($packages as &$package) {
            $package = $this->faceControl($package, ['items']);
        }

        $body = [
            'packages'       => $packages,
            'posting_number' => $postingNumber,
        ];

        return $this->request('POST', "{$this->path}/ship", ['body' => \GuzzleHttp\json_encode($body)]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-section_postings_fbs_act_create_title
     */
    public function actCreate(): int
    {
        $result = $this->request('POST', "{$this->path}/act/create", ['body' => '{}']);

        return $result['id'];
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-section_postings_fbs_act_check_title
     */
    public function actCheckStatus(int $id): array
    {
        return $this->request('POST', "{$this->path}/act/check-status", ['body' => \GuzzleHttp\json_encode(['id' => $id])]);
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-section_postings_fbs_act_get_title
     *
     * @return array|string
     */
    public function actGetPdf(int $id): string
    {
        try {
            $response = $this->getClient()->request('POST', "{$this->path}/act/get-pdf", ['body' => \GuzzleHttp\json_encode(['id' => $id])]);

            return $response->getBody()->getContents();
        } catch (BadResponseException $exc) {
            $this->adaptException($exc);
        }
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_package_label
     *
     * @param array|string $postingNumber
     */
    public function packageLabel($postingNumber): string
    {
        if (is_string($postingNumber)) {
            $postingNumber = [$postingNumber];
        }

        try {
            $response = $this->getClient()->request('POST', "{$this->path}/package-label", ['body' => \GuzzleHttp\json_encode(['posting_number' => $postingNumber])]);

            return $response->getBody()->getContents();
        } catch (BadResponseException $exc) {
            $this->adaptException($exc);
        }
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_arbitration_title
     *
     * @param array|string $postingNumber
     */
    public function arbitration($postingNumber): bool
    {
        if (is_string($postingNumber)) {
            $postingNumber = [$postingNumber];
        }

        $result = $this->request('POST', "{$this->path}/arbitration", ['body' => \GuzzleHttp\json_encode(['posting_number' => $postingNumber])]);

        return 'true' === $result;
    }

    /**
     * @see https://cb-api.ozonru.me/apiref/en/#t-fbs_cancel_title
     */
    public function cancel(string $postingNumber, int $cancelReasonId, string $cancelReasonMessage = null): bool
    {
        $body = [
            'posting_number'        => $postingNumber,
            'cancel_reason_id'      => $cancelReasonId,
            'cancel_reason_message' => $cancelReasonMessage,
        ];
        $result = $this->request('POST', "{$this->path}/cancel", ['body' => \GuzzleHttp\json_encode($body)]);

        return 'true' === $result;
    }

    public function cancelReasons(): array
    {
        return $this->request('POST', "{$this->path}/cancel-reason/list", ['body' => '{}']); //todo свериться с исправленной документацией
    }

    /**
     * @param string|array $postingNumber
     *
     * @return array|string
     */
    public function awaitingDelivery($postingNumber)
    {
        if (is_string($postingNumber)) {
            $postingNumber = [$postingNumber];
        }

        $body = [
            'posting_number' => $postingNumber,
        ];

        return $this->request('POST', "{$this->path}/awaiting-delivery", ['body' => \GuzzleHttp\json_encode($body)]);
    }
}
