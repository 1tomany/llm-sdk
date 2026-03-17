<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;

interface OutputsResourceInterface
{
    public function generate(ProcessQueryRequest $request): GenerateOutputResponse;
}
