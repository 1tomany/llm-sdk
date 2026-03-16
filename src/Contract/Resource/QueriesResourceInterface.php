<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;

interface QueriesResourceInterface
{
    public function compile(CompileRequest $request): CompileResponse;
}
