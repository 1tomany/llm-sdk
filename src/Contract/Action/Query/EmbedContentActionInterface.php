<?php

namespace OneToMany\LlmSdk\Contract\Action\Query;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;

interface EmbedContentActionInterface
{
    /**
     * @throws InvalidArgumentException when the model is not an embedding model
     */
    public function act(CompileRequest|ExecuteRequest $request): EmbedResponse;
}
