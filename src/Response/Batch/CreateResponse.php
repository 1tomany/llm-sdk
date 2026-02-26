<?php

namespace OneToMany\LlmSdk\Response\Batch;

use OneToMany\LlmSdk\Response\BaseResponse;

final readonly class UploadResponse extends BaseResponse
{
    /**
     * @param non-empty-lowercase-string $model
     * @param non-empty-string $uri
     */
    public function __construct(
        string $model,
        private string $uri,
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
}
