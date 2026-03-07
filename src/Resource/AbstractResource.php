<?php

namespace OneToMany\LlmSdk\Resource;

use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract readonly class AbstractResource
{
    /**
     * @param non-empty-string $apiKey
     * @param non-empty-string $apiVersion
     */
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected SerializerInterface $serializer,
        protected string $apiKey,
        protected string $apiVersion,
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return non-empty-string
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * @param 'GET'|'POST'|'PUT'|'DELETE' $method
     * @param array<string, mixed> $options
     */
    protected function doHttpRequest(
        string $method,
        string $url,
        array $options = [],
    ): string {
        try {
            $response = $this->httpClient->request($method, $url, $options);

            /** @var int<100, 599> $statusCode */
            $statusCode = $response->getStatusCode();

            /** @var non-empty-string $content */
            $content = $response->getContent(false);

            if ($statusCode >= 300) {
                $this->handleHttpError($content, $statusCode);
            }
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $content;
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function doHttpGetRequest(string $url, array $options = []): string
    {
        return $this->doHttpRequest('GET', $url, $options);
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function doHttpPostRequest(string $url, array $options = []): string
    {
        return $this->doHttpRequest('POST', $url, $options);
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function doHttpDeleteRequest(string $url, array $options = []): string
    {
        return $this->doHttpRequest('DELETE', $url, $options);
    }

    /**
     * @param positive-int $statusCode
     *
     * @throws RuntimeException when the HTTP request was not successful
     */
    protected function handleHttpError(string $content, int $statusCode): never
    {
        throw new RuntimeException('The HTTP request failed.', $statusCode);
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $type
     * @param array<string, mixed> $context
     *
     * @return T
     */
    protected function doDeserialize(
        string $content,
        string $type,
        string $format = 'json',
        array $context = [],
    ): object {
        try {
            $object = $this->serializer->deserialize($content, $type, $format, $context);
        } catch (SerializerExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $object;
    }
}
