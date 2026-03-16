<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;

interface QueriesResourceInterface
{
    public function compile(CompileQueryRequest $request): CompileQueryResponse;
}
