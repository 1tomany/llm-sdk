<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;

interface QueriesResourceInterface
{
    public function compile(CompileRequest $request): CompileResponse;

    public function execute(ExecuteRequest $request): ExecuteResponse;


}
