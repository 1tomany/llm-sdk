<?php

namespace OneToMany\LlmSdk\Client\Anthropic;

use OneToMany\LlmSdk\Client\BaseClient;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\Anthropic\FilesResource;

final class AnthropicClient extends BaseClient
{
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
     * @see OneToMany\LlmSdk\Client\BaseClient
     */
    public function getApiVersion(): string
    {
        return parent::getApiVersion() ?? '2023-06-01';
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function batches(): BatchesResourceInterface
    {
        throw new RuntimeException('Not implemented!');
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
        throw new RuntimeException('Not implemented!');
    }
}
