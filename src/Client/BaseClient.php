<?php

namespace OneToMany\LlmSdk\Client;

use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\SearchStoresResourceInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BaseClient
{
    protected ?BatchesResourceInterface $batches = null;
    protected ?EmbeddingsResourceInterface $embeddings = null;
    protected ?FilesResourceInterface $files = null;
    protected ?OutputsResourceInterface $outputs = null;
    protected ?QueriesResourceInterface $queries = null;
    protected ?SearchStoresResourceInterface $searchStores = null;

    /**
     * @param non-empty-string $apiKey
     * @param ?non-empty-string $apiVersion
     */
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected DenormalizerInterface&NormalizerInterface&SerializerInterface $serializer,
        protected string $apiKey,
        protected ?string $apiVersion = null,
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
     * @return ?non-empty-string
     */
    public function getApiVersion(): ?string
    {
        return $this->apiVersion;
    }
}
