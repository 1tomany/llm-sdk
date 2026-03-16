<?php

namespace OneToMany\LlmSdk\Action\Embedding;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Query\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;

use function sprintf;

final readonly class CreateEmbeddingAction extends BaseAction implements CreateEmbeddingActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Embedding\CreateEmbeddingActionInterface
     *
     * @throws InvalidArgumentException when the model is not an embedding model
     */
    public function act(CompileRequest|ExecuteRequest $request): EmbedResponse
    {
        if (!$request->getModel()->isEmbedding()) {
            throw new InvalidArgumentException(sprintf('Creating the embedding failed because the model "%s" is not an embedding model.', $request->getModel()->getValue()));
        }

        if ($request instanceof CompileRequest) {
            $request = $this->compileQuery($request)->toExecuteRequest();
        }

        return $this->createClient($request->getVendor())->queries()->embed($request);
    }
}
