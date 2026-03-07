<?php

namespace OneToMany\LlmSdk\Resource\Trait;

use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface as HttpClientHttpExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function implode;
use function ltrim;
use function rtrim;

trait HttpResourceTrait
{
    /**
     * @return non-empty-string
     */
    abstract public function getBaseUrl(): string;

    /**
     * @return non-empty-string
     */
    protected function buildUrl(string ...$paths): string
    {
        return sprintf('%s/%s', rtrim($this->getBaseUrl(), '/'), ltrim(implode('/', $paths), '/'));
    }

    /**
     * @return array<string, int|string|null>
     */
    abstract protected function getBaseHeaders(): array;

    /**
     * @param array<string, int|string|null> $headers
     *
     * @return array<string, int|string|null>
     */
    protected function buildHeaders(array $headers = []): array
    {
        return [...$headers, ...$this->getBaseHeaders()];
    }

    /**
     * @param 'GET'|'POST'|'PUT'|'DELETE' $method
     * @param array<string, mixed> $options
     */
    protected function doRequest(
        string $method,
        string $url,
        array $options = [],
    ): ResponseInterface {
        try {
            /** @var ResponseInterface $response */
            $response = $this->httpClient->request($method, $url, $options);

            /** @var int<100, 599> $statusCode */
            $statusCode = $response->getStatusCode();

            try {
                // Cache and validate the content
                $response->getContent(throw: true);
            } catch (HttpClientHttpExceptionInterface $e) {
                $this->handleRequestError($response->getContent(false), $statusCode);
            }
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $response;
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function doGetRequest(string $url, array $options = []): string
    {
        return $this->doRequest('GET', $url, $options)->getContent();
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function doPostRequest(string $url, array $options = []): string
    {
        return $this->doRequest('POST', $url, $options)->getContent();
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function doDeleteRequest(string $url, array $options = []): string
    {
        return $this->doRequest('DELETE', $url, $options)->getContent();
    }

    /**
     * @throws RuntimeException when the HTTP request was not successful
     */
    abstract protected function handleRequestError(string $content, int $statusCode): never;

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
