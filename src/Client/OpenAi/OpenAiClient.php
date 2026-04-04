<?php

namespace OneToMany\LlmSdk\Client\OpenAi;

use OneToMany\LlmSdk\Client\BaseClient;
use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\StoresResourceInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\OpenAi\BatchesResource;
use OneToMany\LlmSdk\Resource\OpenAi\EmbeddingsResource;
use OneToMany\LlmSdk\Resource\OpenAi\FilesResource;
use OneToMany\LlmSdk\Resource\OpenAi\OutputsResource;
use OneToMany\LlmSdk\Resource\OpenAi\QueriesResource;

final class OpenAiClient extends BaseClient implements ClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public static function getVendor(): Vendor
    {
        return Vendor::OpenAI;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function batches(): BatchesResourceInterface
    {
        $this->batches ??= new BatchesResource($this->httpClient, $this->serializer, $this->getApiKey());

        return $this->batches;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function embeddings(): EmbeddingsResourceInterface
    {
        $this->embeddings ??= new EmbeddingsResource($this->httpClient, $this->serializer, $this->getApiKey());

        return $this->embeddings;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function files(): FilesResourceInterface
    {
        $this->files ??= new FilesResource($this->httpClient, $this->serializer, $this->getApiKey());

        return $this->files;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function outputs(): OutputsResourceInterface
    {
        $this->outputs ??= new OutputsResource($this->httpClient, $this->serializer, $this->getApiKey());

        return $this->outputs;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function queries(): QueriesResourceInterface
    {
        $this->queries ??= new QueriesResource($this->httpClient, $this->serializer, $this->getApiKey());

        return $this->queries;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function stores(): StoresResourceInterface
    {
        throw new RuntimeException('Not implemented!');
    }
}
