<?php

namespace OneToMany\LlmSdk\Action\Embedding;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

final readonly class CreateEmbeddingAction extends BaseAction implements CreateEmbeddingActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface
     */
    public function act(CreateEmbeddingRequest $request): CreateEmbeddingResponse
    {
        return $this->createClient($request->getVendor())->embeddings()->create($request);
    }
}
