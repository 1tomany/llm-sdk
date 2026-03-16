<?php

namespace OneToMany\LlmSdk\Contract\Action\Embedding;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

interface CreateEmbeddingActionInterface
{
    /**
     * @throws InvalidArgumentException when the model is not an embedding model
     */
    public function act(CompileRequest|CreateEmbeddingRequest $request): CreateEmbeddingResponse;
}
