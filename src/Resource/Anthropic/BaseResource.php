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
     * @return array<string, int|string|null>
     */
    protected function buildHttpHeaders(): array
    {
        return [
            'x-api-key' => $this->apiKey,
            'anthropic-version' => $this->apiVersion,
        ];
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\TransportTrait
     */
    protected function handleHttpError(string $content, int $statusCode): never
    {
        $error = $this->doDeserialize($content, Error::class, context: [
            UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
        ]);

        throw new RuntimeException($error->message, $statusCode);
    }
}
