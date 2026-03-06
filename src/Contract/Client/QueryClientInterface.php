<?php

namespace OneToMany\LlmSdk\Contract\Client;

use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;

interface QueryClientInterface
{
    public function compile(CompileRequest $request): CompileResponse;

    public function execute(ExecuteRequest $request): ExecuteResponse;
}
