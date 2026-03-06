<?php

namespace OneToMany\LlmSdk\Response\File;

use OneToMany\LlmSdk\Response\BaseResponse;

final readonly class DeleteResponse extends BaseResponse
{
    public function __construct(
        string $model,
        private string $uri,
    )
    {
        parent::__construct($model);
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
