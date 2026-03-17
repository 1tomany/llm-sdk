<?php

namespace OneToMany\LlmSdk\Contract\Action\Output;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;

interface GenerateOutputActionInterface
{
    /**
     * @throws InvalidArgumentException when the model is not a generative model
     */
    public function act(CompileQueryRequest|ProcessQueryRequest $request): GenerateOutputResponse;
}
