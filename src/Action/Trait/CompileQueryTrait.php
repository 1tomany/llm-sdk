<?php

namespace OneToMany\LlmSdk\Action\Trait;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Response\Query\CompileQueryResponse;

trait CompileQueryTrait
{
    /**
     * @throws InvalidArgumentException when the query does not have any input components
     */
    private function compileQuery(CompileQueryRequest $request): CompileQueryResponse
    {
        if (!$request->hasComponents()) {
            throw new InvalidArgumentException('The query does not have any input components.');
        }

        return $this->createClient($request->getVendor())->queries()->compile($request);
    }
}
