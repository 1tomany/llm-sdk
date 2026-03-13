<?php

namespace OneToMany\LlmSdk\Resource\Anthropic;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\Anthropic\Type\Error\Error;
use OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\SerializerInterface;
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
        protected string $filesVersion = 'files-api-2025-04-14',
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait
     */
    public function getBaseUrl(): string
    {
        return 'https://api.anthropic.com/v1';
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait
     */
    protected function getBaseHeaders(): array
    {
        return ['anthropic-version' => $this->apiVersion, 'x-api-key' => $this->apiKey];
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait
     */
    protected function handleRequestError(ResponseInterface $response): never
    {
        $error = $this->doDenormalize($response->toArray(false), Error::class, [
            UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
        ]);

        throw new RuntimeException($error->message, $response->getStatusCode());
    }
}
