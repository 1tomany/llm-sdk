<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

interface EmbeddingsResourceInterface
{
    public function create(ProcessQueryRequest $request): CreateEmbeddingResponse;
}
