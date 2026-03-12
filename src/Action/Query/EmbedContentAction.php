<?php

namespace OneToMany\LlmSdk\Action\Query;

use OneToMany\LlmSdk\Contract\Action\Query\EmbedContentActionInterface;
use OneToMany\LlmSdk\Contract\Action\Query\GenerateOutputActionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;

final readonly class EmbedContentAction implements EmbedContentActionInterface
{
    public function __construct(private ClientFactory $clientFactory)
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Query\EmbedContentActionInterface
     */
    public function act(CompileRequest|ExecuteRequest $request): EmbedResponse
    {
        $client = $this->clientFactory->create($request->getVendor());

        if ($request instanceof CompileRequest) {
            $request = $client->queries()->compile($request)->toExecuteRequest();
        }

        return $client->queries()->embed($request);
    }
}
