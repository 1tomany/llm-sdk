<?php

namespace OneToMany\AI\Action\Query;

use OneToMany\AI\Contract\Action\Query\ExecuteQueryActionInterface;
use OneToMany\AI\Contract\Client\QueryClientInterface;
use OneToMany\AI\Contract\Factory\ClientFactoryInterface;
use OneToMany\AI\Request\Query\ExecuteRequest;
use OneToMany\AI\Response\Query\ExecuteResponse;

final readonly class ExecuteQueryAction implements ExecuteQueryActionInterface
{
    /**
     * @param ClientFactoryInterface<QueryClientInterface> $clientFactory
     */
    public function __construct(private ClientFactoryInterface $clientFactory)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\Query\ExecuteQueryActionInterface
     */
    public function act(ExecuteRequest $request): ExecuteResponse
    {
        return $this->clientFactory->create($request->getModel())->execute($request);
    }
}
