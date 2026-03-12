<?php

namespace OneToMany\LlmSdk\Action\Batch;

use OneToMany\LlmSdk\Contract\Action\Batch\ReadBatchActionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;

final readonly class ReadBatchAction implements ReadBatchActionInterface
{
    public function __construct(private ClientFactory $clientFactory)
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Batch\ReadBatchActionInterface
     */
    public function act(ReadRequest $request): ReadResponse
    {
        return $this->clientFactory->create($request->getVendor())->batches()->read($request);
    }
}
