<?php

namespace OneToMany\LlmSdk\Action\Batch;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\Batch\ReadBatchActionInterface;
use OneToMany\LlmSdk\Request\Batch\ReadBatchRequest;
use OneToMany\LlmSdk\Response\Batch\ReadBatchResponse;

final readonly class ReadBatchAction extends BaseAction implements ReadBatchActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\Batch\ReadBatchActionInterface
     */
    public function act(ReadBatchRequest $request): ReadBatchResponse
    {
        return $this->createClient($request->getVendor())->batches()->read($request);
    }
}
