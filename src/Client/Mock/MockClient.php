<?php

namespace OneToMany\LlmSdk\Client\Mock;

use OneToMany\LlmSdk\Contract\Client\BatchClientInterface;
use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Client\QueryClientInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;

final class MockClient implements ClientInterface
{
    private ?BatchClientInterface $batchClient = null;
    private ?FilesResourceInterface $fileClient = null;
    private ?QueryClientInterface $queryClient = null;

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
        $this->batchClient ??= new BatchClient();

        return $this->batchClient;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function files(): FilesResourceInterface
    {
        $this->fileClient ??= new FileClient();

        return $this->fileClient;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     */
    public function queries(): QueryClientInterface
    {
        $this->queryClient ??= new QueryClient();

        return $this->queryClient;
    }
}
