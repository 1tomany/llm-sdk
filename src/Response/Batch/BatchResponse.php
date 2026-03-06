<?php

namespace OneToMany\LlmSdk\Response\Batch;

use OneToMany\LlmSdk\Response\BaseResponse;

abstract readonly class BatchResponse extends BaseResponse
{
    public function __construct(
        string $model,
        private string $uri,
        private string $status,
        private ?string $fileUri = null,
    ) {
        parent::__construct($model);
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getFileUri(): ?string
    {
        return $this->fileUri;
    }
}
