<?php

namespace OneToMany\LlmSdk\Action\Output;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Query\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Output\GenerateOutputActionInterface;
use OneToMany\LlmSdk\Request\Output\GenerateOutputRequest;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;

final readonly class GenerateOutputAction extends BaseAction implements GenerateOutputActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Output\GenerateOutputActionInterface
     */
    public function act(CompileRequest|GenerateOutputRequest $request): GenerateOutputResponse
    {
        if ($request instanceof CompileRequest) {
            $request = $this->compileQuery($request)->toGenerateOutputRequest();
        }

        return $this->createClient($request->getVendor())->outputs()->generate($request);
    }
}
