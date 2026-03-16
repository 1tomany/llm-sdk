<?php

namespace OneToMany\LlmSdk\Contract\Action\Query;

use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;

interface CompileQueryActionInterface
{
    public function act(CompileRequest $request): CompileQueryResponse;
}
