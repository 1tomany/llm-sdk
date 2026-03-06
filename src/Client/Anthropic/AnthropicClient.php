<?php

namespace OneToMany\LlmSdk\Client\Anthropic;

use OneToMany\LlmSdk\Contract\Client\BatchClientInterface;
use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Client\FileClientInterface;
use OneToMany\LlmSdk\Contract\Client\QueryClientInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class AnthropicClient implements ClientInterface
{
    private ?FileClientInterface $fileClient = null;

    /**
     * @param non-empty-string $apiKey
     * @param non-empty-string $apiVersion
     */
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
    public function batches(): BatchClientInterface
    {
        throw new RuntimeException('Not implemented!');
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function files(): FileClientInterface
    {
        $this->fileClient ??= new FileClient($this->denormalizer, $this->httpClient, $this->apiKey, $this->apiVersion);

        return $this->fileClient;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function queries(): QueryClientInterface
    {
        throw new RuntimeException('Not implemented!');
    }
}
