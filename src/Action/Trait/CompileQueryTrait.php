<?php

namespace OneToMany\LlmSdk\Action\Trait;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;

trait CompileQueryTrait
{
    /**
     * @throws InvalidArgumentException when the query does not have any user components
     */
    private function compileQuery(CompileRequest $request): CompileResponse
    {
        if (!$request->hasComponents()) {
            throw new InvalidArgumentException('Compiling the query failed because no components have been added to it.');
        }

        return $this->createClient($request->getVendor())->queries()->compile($request);
    }
}
