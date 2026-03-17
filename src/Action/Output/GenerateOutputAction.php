<?php

namespace OneToMany\LlmSdk\Action\Output;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Output\GenerateOutputActionInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;

final readonly class GenerateOutputAction extends BaseAction implements GenerateOutputActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Output\GenerateOutputActionInterface
     */
    public function act(CompileQueryRequest|ProcessQueryRequest $request): GenerateOutputResponse
    {
        if ($request instanceof CompileQueryRequest) {
            $request = $this->compileQuery($request)->toProcessQueryRequest();
        }

        if (!$request->getModel()->isGenerative()) {
            throw new InvalidArgumentException(sprintf('Generating output failed because the model "%s" is not a generative model.', $request->getModel()->getValue()));
        }

        return $this->createClient($request->getVendor())->outputs()->generate($request);
    }
}
