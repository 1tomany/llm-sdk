<?php

namespace OneToMany\LlmSdk\Action\Embedding;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Query\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

final readonly class CreateEmbeddingAction extends BaseAction implements CreateEmbeddingActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface
     */
    public function act(CompileRequest|CreateEmbeddingRequest $request): CreateEmbeddingResponse
    {
        if ($request instanceof CompileRequest) {
            $request = $this->compileQuery($request)->toCreateEmbeddingRequest();
        }

        return $this->createClient($request->getVendor())->embeddings()->create($request);
    }
}
