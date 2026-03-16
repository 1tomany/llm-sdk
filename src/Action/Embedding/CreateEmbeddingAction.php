<?php

namespace OneToMany\LlmSdk\Action\Embedding;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Query\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;

use function sprintf;

final readonly class CreateEmbeddingAction extends BaseAction implements CreateEmbeddingActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface
     *
     * @throws InvalidArgumentException when the model is not an embedding model
     */
    public function act(CreateEmbeddingRequest $request): CreateEmbeddingResponse
    {




        return $this->createClient($request->getVendor())->embeddings()->create($request);
    }
}
