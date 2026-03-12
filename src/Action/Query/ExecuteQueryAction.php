<?php

namespace OneToMany\LlmSdk\Action\Query;

use OneToMany\LlmSdk\Contract\Action\Query\ExecuteQueryActionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;

final readonly class ExecuteQueryAction implements ExecuteQueryActionInterface
{
    public function __construct(private ClientFactory $clientFactory)
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Query\ExecuteQueryActionInterface
     */
    public function act(CompileRequest|ExecuteRequest $request): ExecuteResponse
    {
        $client = $this->clientFactory->create($request->getVendor());

        if ($request instanceof CompileRequest) {
            $request = $client->queries()->compile($request)->toExecuteRequest();
        }

        return $client->queries()->execute($request);
    }
}
