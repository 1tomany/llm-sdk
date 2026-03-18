<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\Error\Error;
use OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface as HttpClientDecodingExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract readonly class BaseResource
{
    use HttpResourceTrait;

    /**
     * @param non-empty-string $apiKey
     * @param non-empty-string $apiVersion
     */
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected DenormalizerInterface&NormalizerInterface&SerializerInterface $serializer,
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
     * @see OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait
     */
    public function getBaseUrl(): string
    {
        return 'https://generativelanguage.googleapis.com';
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait
     */
    protected function getBaseHeaders(): array
    {
        return ['x-goog-api-key' => $this->getApiKey()];
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait
     */
    protected function handleRequestError(ResponseInterface $response): never
    {
        try {
            $error = $this->doDenormalize($response->toArray(false), Error::class, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
            ]);
        } catch (HttpClientDecodingExceptionInterface|LlmSdkExceptionInterface $e) {
            $error = new Error($response->getStatusCode(), $response->getContent(false) ?: $e->getMessage());
        }

        throw new RuntimeException($error->getMessage(), $response->getStatusCode());
    }
}
