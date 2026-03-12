<?php

namespace OneToMany\LlmSdk\Action\Query;

use OneToMany\LlmSdk\Contract\Action\Query\CompileQueryActionInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;

final readonly class CompileQueryAction implements CompileQueryActionInterface
{
    public function __construct(private ClientFactory $clientFactory)
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Query\CompileQueryActionInterface
     *
     * @throws InvalidArgumentException when the request does not have any components
     */
    public function act(CompileRequest $request): CompileResponse
    {
        if (!$request->hasComponents()) {
            throw new InvalidArgumentException('Compiling the query failed because no components have been added to it.');
        }

        return $this->clientFactory->create($request->getVendor())->queries()->compile($request);
    }
}
