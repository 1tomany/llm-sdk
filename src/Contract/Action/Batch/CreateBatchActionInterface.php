<?php

namespace OneToMany\LlmSdk\Contract\Action\Batch;

use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Response\Batch\CreateBatchResponse;

interface CreateBatchActionInterface
{
    public function act(CreateRequest $request): CreateBatchResponse;
}
