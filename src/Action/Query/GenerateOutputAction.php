<?php

namespace OneToMany\LlmSdk\Action\Query;

use OneToMany\LlmSdk\Contract\Action\Query\GenerateOutputActionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;

final readonly class GenerateOutputAction implements GenerateOutputActionInterface
{
    public function __construct(private ClientFactory $clientFactory)
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Query\GenerateOutputActionInterface
     */
    public function act(CompileRequest|ExecuteRequest $request): GenerateResponse
    {
        $client = $this->clientFactory->create($request->getVendor());

        if ($request instanceof CompileRequest) {
            $request = $client->queries()->compile($request)->toExecuteRequest();
        }

        return $client->queries()->generate($request);
    }
}
