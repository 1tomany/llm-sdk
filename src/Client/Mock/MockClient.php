<?php

namespace OneToMany\LlmSdk\Client\Mock;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Resource\Mock\BatchesResource;
use OneToMany\LlmSdk\Resource\Mock\EmbeddingsResource;
use OneToMany\LlmSdk\Resource\Mock\FilesResource;
use OneToMany\LlmSdk\Resource\Mock\QueriesResource;

final class MockClient implements ClientInterface
{
    public function __construct(
        private BatchesResource $batches = new BatchesResource(),
        private EmbeddingsResource $embeddings = new EmbeddingsResource(),
        private FilesResource $files = new FilesResource(),
        private QueriesResource $queries = new QueriesResource(),
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public static function getVendor(): Vendor
    {
        return Vendor::Mock;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function batches(): BatchesResourceInterface
    {
        return $this->batches;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function embeddings(): EmbeddingsResourceInterface
    {
        return $this->embeddings;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function files(): FilesResourceInterface
    {
        return $this->files;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function queries(): QueriesResourceInterface
    {
        return $this->queries;
    }
}
