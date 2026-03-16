<?php

namespace OneToMany\LlmSdk\Contract\Action\Output;

use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;

interface GenerateOutputActionInterface
{
    public function act(CompileRequest|ExecuteRequest $request): GenerateResponse;
}
