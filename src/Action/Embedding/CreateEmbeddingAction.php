<?php

namespace OneToMany\LlmSdk\Action\Embedding;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

final readonly class CreateEmbeddingAction extends BaseAction implements CreateEmbeddingActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface
     */
    public function act(CompileQueryRequest|ProcessQueryRequest $request): CreateEmbeddingResponse
    {
        if ($request instanceof CompileQueryRequest) {
            $request = $this->compileQuery($request)->toCreateEmbeddingRequest();
        }

        if (!$request->getModel()->isEmbedding()) {
            throw new InvalidArgumentException(\sprintf('Creating the embedding failed because the model "%s" is not an embedding model.', $request->getModel()->getValue()));
        }

        return $this->createClient($request->getVendor())->embeddings()->create($request);
    }
}
