<?php

namespace OneToMany\LlmSdk\Response\File;

use OneToMany\LlmSdk\Response\BaseResponse;

final readonly class DeleteResponse extends BaseResponse
{
    /**
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
