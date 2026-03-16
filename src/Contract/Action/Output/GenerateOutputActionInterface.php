<?php

namespace OneToMany\LlmSdk\Contract\Action\Output;

use OneToMany\LlmSdk\Request\Output\GenerateOutputRequest;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;

interface GenerateOutputActionInterface
{
    public function act(CompileQueryRequest|GenerateOutputRequest $request): GenerateOutputResponse;
}
