<?php

namespace OneToMany\LlmSdk\Response\Batch;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Response\Batch\Trait\BatchResponseTrait;

final readonly class ReadBatchResponse
{
    use BatchResponseTrait;

    /**
     * @param non-empty-string $uri
     * @param non-empty-string $status
     * @param ?non-empty-string $file
     */
    public function __construct(
        private Model $model,
        private string $uri,
        private string $status,
        private ?string $file = null,
    ) {
    }
}
