<?php

namespace OneToMany\LlmSdk\Action\Query\Trait;

use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;

trait CompileQueryTrait
{
    /**
     * @throws InvalidArgumentException when the request does not have any user components
     */
    private function compileQuery(CompileRequest $request): CompileResponse
    {
        if (!$request->hasUserComponents()) {
            throw new InvalidArgumentException(sprintf('Compiling the query failed because no components with the role "%s" have been added to it.', Role::User->getValue()));
        }

        return $this->createClient($request->getVendor())->queries()->compile($request);
    }
}
