<?php

namespace OneToMany\LlmSdk\Action\Query;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Query\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Query\GenerateOutputActionInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;

final readonly class GenerateOutputAction extends BaseAction implements GenerateOutputActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Query\GenerateOutputActionInterface
     * @see OneToMany\LlmSdk\Action\Query\Trait\CompileQueryTrait
     */
    public function act(CompileRequest|ExecuteRequest $request): GenerateResponse
    {
        if ($request instanceof CompileRequest) {
            $request = $this->compileQuery($request)->toExecuteRequest();
        }

        return $this->createClient($request->getVendor())->queries()->generate($request);
    }
}
