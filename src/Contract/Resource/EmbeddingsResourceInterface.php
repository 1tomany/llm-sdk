<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

interface EmbeddingsResourceInterface
{
    public function create(CreateEmbeddingRequest $request): CreateEmbeddingResponse;
}
