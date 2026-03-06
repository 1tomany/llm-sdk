<?php

namespace OneToMany\LlmSdk\Client\Mock;

use OneToMany\LlmSdk\Contract\Client\BatchClientInterface;
use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Client\QueryClientInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Resource\Mock\BatchesResource;
use OneToMany\LlmSdk\Resource\Mock\FilesResource;
use OneToMany\LlmSdk\Resource\Mock\QueriesResource;

final class MockClient implements ClientInterface
{
    private ?BatchClientInterface $batchesResource = null;
    private ?FilesResourceInterface $filesResource = null;
    private ?QueryClientInterface $queriesResource = null;

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
    public function batches(): BatchClientInterface
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
    public function queries(): QueryClientInterface
    {
        $this->queriesResource ??= new QueriesResource();

        return $this->queriesResource;
    }
}
