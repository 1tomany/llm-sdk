<?php

namespace OneToMany\LlmSdk\Request\Embedding;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Request\BaseRequest;

class CreateEmbeddingRequest extends BaseRequest
{
    /**
     * @param array<string, mixed> $request
     */
    public function __construct(
        string|Model|null $model,
        private array $request,
    ) {
        parent::__construct($model);
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }
}
