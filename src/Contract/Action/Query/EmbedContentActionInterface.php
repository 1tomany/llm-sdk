<?php

namespace OneToMany\LlmSdk\Contract\Action\Query;

use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;

interface EmbedContentActionInterface
{
    public function act(CompileRequest|ExecuteRequest $request): EmbedResponse;
}
