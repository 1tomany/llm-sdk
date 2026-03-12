<?php

namespace OneToMany\LlmSdk\Action\Batch;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\Batch\ReadBatchActionInterface;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;

final readonly class ReadBatchAction extends BaseAction implements ReadBatchActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\Batch\ReadBatchActionInterface
     */
    public function act(ReadRequest $request): ReadResponse
    {
        return $this->createClient($request->getVendor())->batches()->read($request);
    }
}
