<?php

namespace OneToMany\LlmSdk\Contract\Action\Embedding;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

interface CreateEmbeddingActionInterface
{
    /**
     * @throws InvalidArgumentException when the model is not an embedding model
     */
    public function act(CompileQueryRequest|ProcessQueryRequest $request): CreateEmbeddingResponse;
}
