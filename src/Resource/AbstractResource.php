<?php

namespace OneToMany\LlmSdk\Resource;

use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract readonly class AbstractResource
{
    public function __construct(
        protected DenormalizerInterface $denormalizer,
        protected HttpClientInterface $httpClient,
        protected string $apiKey,
        protected string $apiVersion,
    ) {
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<mixed>
     */
    protected function doRequest(string $method, string $url, array $options = []): array
    {
        try {
            $response = $this->httpClient->request($method, $url, $options);

            /** @var int<100, 599> $statusCode */
            $statusCode = $response->getStatusCode();

            /** @var array<mixed> $content */
            $content = $response->toArray(false);

            if ($statusCode >= 300) {
                throw new RuntimeException($this->extractErrorMessage($content), $statusCode);
            }
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $content;
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $type
     * @param array<string, mixed> $context
     *
     * @return T
     */
    protected function denormalize(mixed $content, string $type, array $context = []): object
    {
        try {
            $object = $this->denormalizer->denormalize($content, $type, null, $context);
        } catch (SerializerExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $object;
    }

    // /**
    //  * @return array<mixed>
    //  */
    // abstract protected function buildAuthOptions(): array;

    /**
     * @param array<mixed> $content
     */
    abstract protected function extractErrorMessage(array $content): string;
}
