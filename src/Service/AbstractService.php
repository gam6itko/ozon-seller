<?php declare(strict_types=1);

namespace Gam6itko\OzonSeller\Service;

use Gam6itko\OzonSeller\Exception\OzonSellerException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Client\RequestExceptionInterface;
use Psr\Http\Message\RequestInterface;

/**
 * @author Alexander Strizhak <gam6itko@gmail.com>
 */
abstract class AbstractService
{
    /** @var array */
    private $config;

    /** @var ClientInterface */
    protected $client;

    public function __construct(array $config, ClientInterface $client)
    {
        $this->parseConfig($config);
        $this->client = $client;
    }

    protected function getDefaultHost(): string
    {
        return 'https://api-seller.ozon.ru';
    }

    private function parseConfig(array $config): void
    {
        $keys = ['clientId', 'apiKey', 'host'];

        if (!$this->isAssoc($config)) {
            if (count($config) > 3) {
                throw new \LogicException('To many config parameters');
            }
            $config = array_combine($keys, array_pad($config, 3, null));
        }

        if (empty($config['clientId']) || empty($config['apiKey'])) {
            throw new \LogicException('Not defined mandatory config parameters `clientId` or `apiKey`');
        }

        if (!empty($config['host'])) {
            $url = parse_url($config['host']);
            $config['host'] = "{$url['scheme']}://{$url['host']}";
        } else {
            $config['host'] = rtrim($this->getDefaultHost(), '/');
        }

        $this->config = $this->faceControl($config, $keys);
    }

    /**
     * Filters unexpected array keys.
     */
    protected function faceControl(array $query, array $whitelist): array
    {
        return array_intersect_key($query, array_flip($whitelist));
    }

    protected function createRequest(string $method, string $uri = '', $body = null): RequestInterface
    {
        if (is_array($body)) {
            $body = json_encode($body);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \RuntimeException('json_encode error: '.json_last_error_msg());
            }
        }

        return new Request(
            $method,
            $this->config['host'].$uri,
            [
                'Client-Id'    => $this->config['clientId'],
                'Api-Key'      => $this->config['apiKey'],
                'Content-Type' => 'application/json',
            ],
            $body
        );
    }

    /**
     * @param array|string|null $body
     */
    protected function request(string $method, string $uri = '', $body = null, bool $parseResultAsJson = true, bool $returnOnlyResult = true)
    {
        try {
            $request = $this->createRequest($method, $uri, $body);
            $response = $this->client->sendRequest($request);
            $responseBody = $response->getBody();

            // nyholm/psr7
            if ($response->getStatusCode() >= 400) {
                $this->throwOzonException($responseBody->getContents());
            }

            if (!$parseResultAsJson) {
                return $responseBody->getContents();
            }

            $arr = json_decode($responseBody->getContents(), true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \RuntimeException('Invalid json response: '.$arr);
            }

            if (isset($arr['result']) && $returnOnlyResult) {
                return $arr['result'];
            }

            return $arr;
        } catch (RequestExceptionInterface $exc) {
            // guzzle
            $contents = $exc->getResponse()->getBody()->getContents();
            $this->throwOzonException($contents);
        }
    }

    protected function throwOzonException(string $responseBodyContents): void
    {
        $errorData = json_decode($responseBodyContents, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new OzonSellerException($responseBodyContents);
        }

        if (!class_exists($className = $this->getExceptionClassByName($errorData['error']['code']))) {
            throw new OzonSellerException($responseBodyContents);
        }

        $errorData = array_merge([
            'message' => '',
            'data'    => [],
        ], $errorData['error']);

        $refClass = new \ReflectionClass($className);
        /** @var \Throwable $instance */
        $instance = $refClass->newInstance($errorData['message'], $errorData['data']);
        throw $instance;
    }

    private function getExceptionClassByName(string $code): string
    {
        $parts = array_filter(explode('_', strtolower($code)));
        // 'error' будет заменен на Exception
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
