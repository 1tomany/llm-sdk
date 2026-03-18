<?php

namespace OneToMany\LlmSdk\Action\Embedding;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

use function sprintf;

final readonly class CreateEmbeddingAction extends BaseAction implements CreateEmbeddingActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface
     */
    public function act(CompileQueryRequest|ProcessQueryRequest $request): CreateEmbeddingResponse
    {
        if (!$request->getModel()->isEmbedding()) {
            throw new InvalidArgumentException(sprintf('The model "%s" is not an embedding model.', $request->getModel()->getValue()));
        }

        if ($request instanceof CompileQueryRequest) {
            $request = $this->compileQuery($request)->toProcessQueryRequest();
        }

        return $this->createClient($request->getVendor())->embeddings()->create($request);
    }
}
