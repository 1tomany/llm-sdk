<?php

namespace OneToMany\LlmSdk\Action\Batch;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\Batch\CreateBatchActionInterface;
use OneToMany\LlmSdk\Request\Batch\CreateBatchRequest;
use OneToMany\LlmSdk\Response\Batch\CreateBatchResponse;

final readonly class CreateBatchAction extends BaseAction implements CreateBatchActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\Batch\CreateBatchActionInterface
     */
    public function act(CreateBatchRequest $request): CreateBatchResponse
    {
        return $this->createClient($request->getVendor())->batches()->create($request);
    }
}
