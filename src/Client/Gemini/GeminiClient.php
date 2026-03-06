<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\BaseClient;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Resource\Gemini\BatchesResource;
use OneToMany\LlmSdk\Resource\Gemini\FilesResource;
use OneToMany\LlmSdk\Resource\Gemini\QueriesResource;

final class GeminiClient extends BaseClient
{
    protected ?string $apiVersion = 'v1beta';

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
        $this->batches ??= new BatchesResource($this->httpClient, $this->serializer, $this->apiKey, $this->apiVersion);

        return $this->batches;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function files(): FilesResourceInterface
    {
        $this->files ??= new FilesResource($this->httpClient, $this->serializer, $this->apiKey, $this->apiVersion);

        return $this->files;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function queries(): QueriesResourceInterface
    {
        $this->queries ??= new QueriesResource($this->httpClient, $this->serializer, $this->apiKey, $this->apiVersion);

        return $this->queries;
    }
}
