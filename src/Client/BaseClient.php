<?php

namespace OneToMany\LlmSdk\Client;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class BaseClient implements ClientInterface
{
    protected HttpClientInterface $httpClient;
    protected ?BatchesResourceInterface $batches = null;
    protected ?FilesResourceInterface $files = null;
    protected ?QueriesResourceInterface $queries = null;

    public function __construct(
        ?HttpClientInterface $httpClient = null,
        protected ?string $apiKey = null,
    ) {
        $this->httpClient = $httpClient ?? HttpClient::create();
    }
}
