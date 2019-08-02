<?php

namespace Gam6itko\OzonSeller\Service;


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
        $this->host = trim($host, '/') . '/';
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
     * Filters unexpected array keys
     * @param array $query
     * @param array $whitelist
     * @return array
     */
    protected function faceControl(array $query, array $whitelist): array
    {
        return array_intersect_key($query, array_flip($whitelist));
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

        $className = $this->getExceptionClassByName($errorData['code']);
        $errorData = array_merge([
            'message' => '',
            'data'    => []
        ], $errorData);

        try {
            $refClass = new \ReflectionClass($className);
            /** @var \Throwable $instance */
            $instance = $refClass->newInstance($errorData['message'], $errorData['data']);
            throw $instance;
        } catch (\ReflectionException $re) {
            throw $clientException;
        }
    }

    private function getExceptionClassByName(string $code): string
    {
        $parts = explode('_', strtolower($code));
        $parts = array_map('ucfirst', $parts);
        $name = implode('', $parts);

        return "Gam6itko\\OzonSeller\\Exception\\{$name}Exception";
    }

    protected function ensureCollection(array $arr)
    {
        $isAssoc = array_keys($arr) !== range(0, count($arr) - 1);
        return $isAssoc ? [$arr] : $arr;
    }
}