<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;

interface QueriesResourceInterface
{
    public function compile(CompileRequest $request): CompileResponse;

    public function generate(ExecuteRequest $request): GenerateResponse;

    public function embed(ExecuteRequest $request): EmbedResponse;
}
