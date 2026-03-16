<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\Output\GenerateOutputRequest;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;

interface OutputsResourceInterface
{
    public function generate(GenerateOutputRequest $request): GenerateOutputResponse;
}
