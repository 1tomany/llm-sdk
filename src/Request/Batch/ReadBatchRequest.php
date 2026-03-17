<?php

namespace OneToMany\LlmSdk\Request\Batch;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Request\BaseRequest;

class ReadBatchRequest extends BaseRequest
{
    /**
     * @param non-empty-string $uri
     */
    public function __construct(
        string|Model|null $model,
        private readonly string $uri,
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
