<?php

namespace OneToMany\LlmSdk\Action\Query;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Query\CompileQueryActionInterface;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;

final readonly class CompileQueryAction extends BaseAction implements CompileQueryActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Query\CompileQueryActionInterface
     */
    public function act(CompileQueryRequest $request): CompileQueryResponse
    {
        return $this->compileQuery($request);
    }
}
