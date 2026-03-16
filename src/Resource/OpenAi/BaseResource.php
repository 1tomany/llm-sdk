<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Error\Error;
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
     */
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected DenormalizerInterface&NormalizerInterface&SerializerInterface $serializer,
        protected string $apiKey,
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
     * @see OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait
     */
    public function getBaseUrl(): string
    {
        return 'https://api.openai.com/v1';
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait
     */
    protected function getBaseHeaders(): array
    {
        return [];
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
