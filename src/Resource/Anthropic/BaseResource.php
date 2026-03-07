<?php

namespace OneToMany\LlmSdk\Resource\Anthropic;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\Anthropic\Type\Error\Error;
use OneToMany\LlmSdk\Resource\Trait\TransportTrait;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract readonly class BaseResource
{
    use TransportTrait;

    /**
     * @param non-empty-string $apiKey
     * @param non-empty-string $apiVersion
     */
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected SerializerInterface $serializer,
        protected string $apiKey,
        protected string $apiVersion,
        protected string $filesApiVersion = 'files-api-2025-04-14',
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\TransportTrait
     */
    public function getBaseUrl(): string
    {
        return 'https://api.anthropic.com/v1';
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\TransportTrait
     */
    protected function getBaseHeaders(): array
    {
        return ['anthropic-version' => $this->apiVersion, 'x-api-key' => $this->apiKey];
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\TransportTrait
     */
    protected function handleRequestError(string $content, int $statusCode): never
    {
        $error = $this->doDeserialize($content, Error::class, context: [
            UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
        ]);

        throw new RuntimeException($error->message, $statusCode);
    }
}
