<?php

namespace Gam6itko\OzonSeller\Service;

use Gam6itko\OzonSeller\Exception\BadRequestException;
use Gam6itko\OzonSeller\Exception\InternalException;
use Gam6itko\OzonSeller\Exception\NotFoundException;
use Gam6itko\OzonSeller\Exception\ValidationException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

abstract class AbstractService
{
    /** @var string */
    private $host;

    /** @var int */
    private $clientId;

    /** @var string */
    private $apiKey;

    /** @var Client */
    private $client;

    /**
     * CategoriesService constructor.
     * @param string $host
     * @param int $clientId
     * @param string $apiKey
     */
    public function __construct(int $clientId, string $apiKey, string $host = 'https://api-seller.ozon.ru/')
    {
        $this->clientId = $clientId;
        $this->apiKey = $apiKey;
        $this->host = $host;
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        if (null === $this->client) {
            $this->client = new Client([
                'base_uri' => $this->host,
                'headers'  => [
                    'Client-Id'    => $this->clientId,
                    'Api-Key'      => $this->apiKey,
                    'Content-Type' => 'application/json',

                ]
            ]);
        }
        return $this->client;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @return string|array
     * @throws \Exception
     */
    protected function request(string $method, string $uri = '', array $options = [])
    {
        try {
            $response = $this->getClient()->request($method, $uri, $options);
            return \GuzzleHttp\json_decode($response->getBody()->getContents(), true)['result'];
        } catch (BadResponseException $exc) {
            $this->adaptException($exc);
        }
    }

    private function adaptException(BadResponseException $clientException)
    {
        try {
            $body = $clientException->getResponse()->getBody()->getContents();
            $errorData = \GuzzleHttp\json_decode($body, true)['error'];
        } catch (\InvalidArgumentException $exc) {
            throw $clientException;
        }

        if (!isset($errorData['data'])) {
            $errorData['data'] = [];
        }

        switch ($errorData['code']) {
            case 'internal_error':
                throw new InternalException($errorData['message']);
            case 'bad_request':
                throw new BadRequestException($errorData['message'], $errorData['data']);
            case 'not_found':
                throw new NotFoundException($errorData['message']);
            case 'validation':
                throw new ValidationException($errorData['message'], $errorData['data']);
            default:
                throw $clientException;
        }
    }
}