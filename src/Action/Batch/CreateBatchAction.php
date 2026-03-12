<?php

namespace OneToMany\LlmSdk\Action\Batch;

use OneToMany\LlmSdk\Contract\Action\Batch\CreateBatchActionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;

final readonly class CreateBatchAction implements CreateBatchActionInterface
{
    public function __construct(private ClientFactory $clientFactory)
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Batch\CreateBatchActionInterface
     */
    public function act(CreateRequest $request): CreateResponse
    {
        return $this->clientFactory->create($request->getVendor())->batches()->create($request);
    }
}
