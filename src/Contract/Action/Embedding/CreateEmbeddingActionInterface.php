<?php

namespace OneToMany\LlmSdk\Contract\Action\Embedding;

use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

interface CreateEmbeddingActionInterface
{
    public function act(CompileQueryRequest|ProcessQueryRequest $request): CreateEmbeddingResponse;
}
