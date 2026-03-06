<?php

namespace OneToMany\LlmSdk\Client;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class BaseClient implements ClientInterface
{
    protected ?BatchesResourceInterface $batches = null;
    protected ?FilesResourceInterface $files = null;
    protected ?QueriesResourceInterface $queries = null;

    public function __construct(
        protected HttpClientInterface $httpClient,
        protected SerializerInterface $serializer,
        protected string $apiKey,
        protected string $apiVersion,
    ) {
    }
}
