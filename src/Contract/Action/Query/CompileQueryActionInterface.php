<?php

namespace OneToMany\LlmSdk\Contract\Action\Query;

use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;

interface CompileQueryActionInterface
{
    public function act(CompileQueryRequest $request): CompileQueryResponse;
}
