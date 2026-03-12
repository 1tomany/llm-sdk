<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\BaseClient;
use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Resource\Gemini\BatchesResource;
use OneToMany\LlmSdk\Resource\Gemini\FilesResource;
use OneToMany\LlmSdk\Resource\Gemini\QueriesResource;

final class GeminiClient extends BaseClient implements ClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public static function getVendor(): Vendor
    {
        return Vendor::Gemini;
    }

    /**
     * @see OneToMany\LlmSdk\Client\BaseClient
     */
    public function getApiVersion(): string
    {
        return parent::getApiVersion() ?: 'v1beta';
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function batches(): BatchesResourceInterface
    {
        $this->batches ??= new BatchesResource($this->httpClient, $this->serializer, $this->getApiKey(), $this->getApiVersion());

        return $this->batches;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function files(): FilesResourceInterface
    {
        $this->files ??= new FilesResource($this->httpClient, $this->serializer, $this->getApiKey(), $this->getApiVersion());

        return $this->files;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function queries(): QueriesResourceInterface
    {
        $this->queries ??= new QueriesResource($this->httpClient, $this->serializer, $this->getApiKey(), $this->getApiVersion());

        return $this->queries;
    }
}
