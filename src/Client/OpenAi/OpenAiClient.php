<?php

namespace OneToMany\LlmSdk\Client\OpenAi;

use OneToMany\LlmSdk\Client\BaseClient;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Resource\OpenAi\BatchesResource;
use OneToMany\LlmSdk\Resource\OpenAi\FilesResource;
use OneToMany\LlmSdk\Resource\OpenAi\QueriesResource;

final class OpenAiClient extends BaseClient
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     *
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public static function getModels(): array
    {
        return [
            'gpt-5.4-pro',
            'gpt-5.4-pro-2026-03-05',
            'gpt-5.4',
            'gpt-5.4-2026-03-05',
            'gpt-5.2-pro',
            'gpt-5.2-pro-2025-12-11',
            'gpt-5.2',
            'gpt-5.2-2025-12-11',
            'gpt-5.1',
            'gpt-5.1-2025-11-13',
            'gpt-5-pro',
            'gpt-5-pro-2025-10-06',
            'gpt-5',
            'gpt-5-2025-08-07',
            'gpt-5-mini',
            'gpt-5-mini-2025-08-07',
            'gpt-5-nano',
            'gpt-5-nano-2025-08-07',
            'gpt-4.1',
            'gpt-4.1-2025-04-14',
        ];
    }

    /**
     * @see OneToMany\LlmSdk\Client\BaseClient
     *
     * @return non-empty-string
     */
    public function getApiVersion(): string
    {
        return parent::getApiVersion() ?? 'v1';
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function batches(): BatchesResourceInterface
    {
        $this->batches ??= new BatchesResource($this->httpClient, $this->serializer, $this->apiKey, $this->getApiVersion());

        return $this->batches;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function files(): FilesResourceInterface
    {
        $this->files ??= new FilesResource($this->httpClient, $this->serializer, $this->apiKey, $this->getApiVersion());

        return $this->files;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function queries(): QueriesResourceInterface
    {
        $this->queries ??= new QueriesResource($this->httpClient, $this->serializer, $this->apiKey, $this->getApiVersion());

        return $this->queries;
    }
}
