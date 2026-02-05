<?php

namespace OneToMany\AI\Action\Query;

use OneToMany\AI\Contract\Action\Query\CompileQueryActionInterface;
use OneToMany\AI\Contract\Client\QueryClientInterface;
use OneToMany\AI\Contract\Factory\ClientFactoryInterface;
use OneToMany\AI\Exception\InvalidArgumentException;
use OneToMany\AI\Request\Query\CompileRequest;
use OneToMany\AI\Response\Query\CompileResponse;

final readonly class CompileQueryAction implements CompileQueryActionInterface
{
    /**
     * @param ClientFactoryInterface<QueryClientInterface> $clientFactory
     */
    public function __construct(private ClientFactoryInterface $clientFactory)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\Query\CompileQueryActionInterface
     *
     * @throws InvalidArgumentException the request does not have any components
     */
    public function act(CompileRequest $request): CompileResponse
    {
        if (!$request->hasComponents()) {
            throw new InvalidArgumentException('Compiling the query failed because no components have been added to it.');
        }

        return $this->clientFactory->create($request->getModel())->compile($request);
    }
}
