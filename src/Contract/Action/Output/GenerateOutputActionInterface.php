<?php

namespace OneToMany\LlmSdk\Contract\Action\Output;

use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;

interface GenerateOutputActionInterface
{
    public function act(CompileQueryRequest|ProcessQueryRequest $request): GenerateOutputResponse;
}
