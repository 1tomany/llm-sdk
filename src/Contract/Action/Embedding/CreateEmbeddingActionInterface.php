<?php

namespace OneToMany\LlmSdk\Contract\Action\Embedding;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

interface CreateEmbeddingActionInterface
{
    public function act(CompileQueryRequest|CreateEmbeddingRequest $request): CreateEmbeddingResponse;
}
