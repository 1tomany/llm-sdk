<?php

namespace OneToMany\LlmSdk\Request\File;

use OneToMany\LlmSdk\Request\BaseRequest;

class DeleteRequest extends BaseRequest
{
    public function __construct(
        string $model,
        private string $uri,
    ) {
        parent::__construct($model);
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
