<?php

namespace OneToMany\LlmSdk\Action\Query;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Query\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Query\GenerateOutputActionInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;

use function sprintf;

final readonly class GenerateOutputAction extends BaseAction implements GenerateOutputActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Query\GenerateOutputActionInterface
     *
     * @throws InvalidArgumentException when the model is an embedding model
     */
    public function act(CompileRequest|ExecuteRequest $request): GenerateResponse
    {
        if ($request->getModel()->isEmbedding()) {
            throw new InvalidArgumentException(sprintf('Generating output failed because the model "%s" is an embedding model.', $request->getModel()->getValue()));
        }

        if ($request instanceof CompileRequest) {
            $request = $this->compileQuery($request)->toExecuteRequest();
        }

        return $this->createClient($request->getVendor())->queries()->generate($request);
    }
}
