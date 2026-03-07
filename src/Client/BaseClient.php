<?php

namespace OneToMany\LlmSdk\Client;

use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BaseClient
{
    protected ?BatchesResourceInterface $batches = null;
    protected ?FilesResourceInterface $files = null;
    protected ?QueriesResourceInterface $queries = null;

    /**
     * @param non-empty-string $apiKey
     * @param ?non-empty-string $apiVersion
     */
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected SerializerInterface $serializer,
        protected string $apiKey,
        protected ?string $apiVersion = null,
    ) {
    }
}
