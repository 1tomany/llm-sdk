<?php

namespace OneToMany\LlmSdk\Resource\Trait;

use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

trait TransportTrait
{
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

            $content = $response->getContent(false);
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        if ($statusCode >= 300) {
            $this->handleHttpError($content, $statusCode);
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
     * @throws RuntimeException when the HTTP request was not successful
     */
    abstract protected function handleHttpError(string $content, int $statusCode): never;

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
