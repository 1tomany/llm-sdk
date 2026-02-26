<?php

namespace OneToMany\LlmSdk\Response\Batch;

use OneToMany\LlmSdk\Response\BaseResponse;

final readonly class CreateResponse extends BaseResponse
{
    /**
     * @param non-empty-lowercase-string $model
     * @param non-empty-string $uri
     * @param ?non-empty-string $fileUri
     */
    public function __construct(
        string $model,
        private string $uri,
        private ?string $fileUri,
        private bool $isCompleted = false,
        private bool $isFailed = false,
        private bool $isCancelled = false,
        private bool $isExpired = false,
    ) {
        parent::__construct($model);
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return ?non-empty-string
     */
    public function getFileUri(): ?string
    {
        return $this->fileUri;
    }

    public function isCompleted(): bool
    {
        return $this->isCompleted;
    }

    public function isFailed(): bool
    {
        return $this->isFailed;
    }

    public function isCancelled(): bool
    {
        return $this->isCancelled;
    }

    public function isExpired(): bool
    {
        return $this->isExpired;
    }
}
