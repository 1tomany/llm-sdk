<?php

namespace OneToMany\LlmSdk\Client\Mock;

use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Resource\Mock\BatchesResource;
use OneToMany\LlmSdk\Resource\Mock\FilesResource;
use OneToMany\LlmSdk\Resource\Mock\QueriesResource;

final class MockClient implements ClientInterface
{
    private ?BatchesResourceInterface $batchesResource = null;
    private ?FilesResourceInterface $filesResource = null;
    private ?QueriesResourceInterface $queriesResource = null;

    public function __construct()
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public static function getModels(): array
    {
        return ['mock'];
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function batches(): BatchesResourceInterface
    {
        $this->batchesResource ??= new BatchesResource();

        return $this->batchesResource;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function files(): FilesResourceInterface
    {
        $this->filesResource ??= new FilesResource();

        return $this->filesResource;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function queries(): QueriesResourceInterface
    {
        $this->queriesResource ??= new QueriesResource();

        return $this->queriesResource;
    }
}
