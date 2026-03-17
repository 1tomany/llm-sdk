<?php

namespace OneToMany\LlmSdk\Action\Embedding;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

final readonly class CreateEmbeddingAction extends BaseAction implements CreateEmbeddingActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface
     */
    public function act(CompileQueryRequest|CreateEmbeddingRequest $request): CreateEmbeddingResponse
    {
        if ($request instanceof CompileQueryRequest) {
            $request = $this->compileQuery($request)->toCreateEmbeddingRequest();
        }

        return $this->createClient($request->getVendor())->embeddings()->create($request);
    }
}
