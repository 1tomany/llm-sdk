<?php

namespace OneToMany\LlmSdk\Action\Query;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Query\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Query\CompileQueryActionInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;

final readonly class CompileQueryAction extends BaseAction implements CompileQueryActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Query\CompileQueryActionInterface
     */
    public function act(CompileRequest $request): CompileResponse
    {
        return $this->compileQuery($request);
    }
}
