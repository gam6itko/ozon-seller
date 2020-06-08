<?php

namespace Gam6itko\OzonSeller\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
abstract class AbstractService
{
    use LoggerAwareTrait;

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
     */
    public function __construct(int $clientId, string $apiKey, string $host = 'https://api-seller.ozon.ru/')
    {
        $this->clientId = $clientId;
        $this->apiKey = $apiKey;
        $this->host = trim($host, '/').'/';
        $this->logger = new NullLogger();
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
                ],
            ]);
        }

        return $this->client;
    }

    /**
     * Filters unexpected array keys.
     */
    protected function faceControl(array $query, array $whitelist): array
    {
        return array_intersect_key($query, array_flip($whitelist));
    }

    /**
     * @return string|array
     */
    protected function request(string $method, string $uri = '', array $options = [])
    {
        try {
            $this->logger->debug("request {$method} {$uri}", $options);
            $response = $this->getClient()->request($method, $uri, $options);

            $arr = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
            $this->logger->debug("response {$method} {$uri}", $arr);

            if (isset($arr['result'])) {
                return $arr['result'];
            }

            return $arr;
        } catch (BadResponseException $exc) {
            $this->adaptException($exc);
        }
    }

    protected function adaptException(BadResponseException $clientException)
    {
        try {
            $this->logger->error($clientException->getMessage());
            $body = $clientException->getResponse()->getBody()->getContents();
            $errorData = \GuzzleHttp\json_decode($body, true)['error'];
        } catch (\InvalidArgumentException $exc) {
            throw $clientException;
        }

        $className = $this->getExceptionClassByName($errorData['code']);
        $errorData = array_merge([
            'message' => '',
            'data'    => [],
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
        if ('error' === end($parts)) {
            unset($parts[key($parts)]);
        }
        $parts = array_map('ucfirst', $parts);
        $name = implode('', $parts);

        return "Gam6itko\\OzonSeller\\Exception\\{$name}Exception";
    }

    protected function ensureCollection(array $arr)
    {
        return $this->isAssoc($arr) ? [$arr] : $arr;
    }

    protected function isAssoc(array $arr): bool
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
