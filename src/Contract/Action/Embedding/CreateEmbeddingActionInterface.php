<?php

namespace OneToMany\LlmSdk\Contract\Action\Embedding;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;

interface CreateEmbeddingActionInterface
{
    /**
     * @throws InvalidArgumentException when the model is not an embedding model
     */
    public function act(CompileRequest|ExecuteRequest $request): EmbedResponse;
}
