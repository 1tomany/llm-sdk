<?php

namespace OneToMany\LlmSdk\Contract\Action\Batch;

use OneToMany\LlmSdk\Request\Batch\CreateBatchRequest;
use OneToMany\LlmSdk\Response\Batch\CreateBatchResponse;

interface CreateBatchActionInterface
{
    public function act(CreateBatchRequest $request): CreateBatchResponse;
}
