<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GeminiClient implements ClientInterface
{
    private ?BatchesResourceInterface $batchesResource = null;
    private ?FilesResourceInterface $filesResource = null;
    private ?QueriesResourceInterface $queriesResource = null;

    public function __construct(
        private readonly DenormalizerInterface $denormalizer,
        private readonly HttpClientInterface $httpClient,
        #[\SensitiveParameter] private string $apiKey,
        private readonly string $apiVersion = 'v1beta',
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
            'gemini-3.1-pro-preview',
            'gemini-3.1-flash-lite-preview',
            'gemini-3-pro-preview',
            'gemini-3-flash-preview',
            'gemini-2.5-pro',
            'gemini-2.5-flash',
            'gemini-2.5-flash-preview-09-2025',
            'gemini-2.5-flash-lite',
            'gemini-2.5-flash-lite-preview-09-2025',
        ];
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function batches(): BatchesResourceInterface
    {
        $this->batchesResource ??= new BatchClient($this->denormalizer, $this->httpClient, $this->apiKey, $this->apiVersion);

        return $this->batchesResource;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function files(): FilesResourceInterface
    {
        $this->filesResource ??= new FileClient($this->denormalizer, $this->httpClient, $this->apiKey, $this->apiVersion);

        return $this->filesResource;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function queries(): QueriesResourceInterface
    {
        $this->queriesResource ??= new QueryClient($this->denormalizer, $this->httpClient, $this->apiKey, $this->apiVersion);

        return $this->queriesResource;
    }
}
