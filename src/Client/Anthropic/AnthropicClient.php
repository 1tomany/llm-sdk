<?php

namespace OneToMany\LlmSdk\Client\Anthropic;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\Anthropic\FilesResource;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class AnthropicClient implements ClientInterface
{
    private ?FilesResourceInterface $filesResource = null;

    public function __construct(
        private DenormalizerInterface $denormalizer,
        private HttpClientInterface $httpClient,
        #[\SensitiveParameter] private string $apiKey,
        private string $apiVersion = '2023-06-01',
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     *
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public static function getModels(): array
    {
        return [
            'claude-opus-4-6',
            'claude-sonnet-4-5',
            'claude-sonnet-4-5-20250929',
            'claude-haiku-4-5',
            'claude-haiku-4-5-20251001',
        ];
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function batches(): BatchesResourceInterface
    {
        throw new RuntimeException('Not implemented!');
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function files(): FilesResourceInterface
    {
        $this->filesResource ??= new FilesResource($this->denormalizer, $this->httpClient, $this->apiKey, $this->apiVersion);

        return $this->filesResource;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function queries(): QueriesResourceInterface
    {
        throw new RuntimeException('Not implemented!');
    }
}
