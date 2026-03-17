<?php

namespace OneToMany\LlmSdk\Contract\Action\Batch;

use OneToMany\LlmSdk\Request\Batch\ReadBatchRequest;
use OneToMany\LlmSdk\Response\Batch\ReadBatchResponse;

interface ReadBatchActionInterface
{
    public function act(ReadBatchRequest $request): ReadBatchResponse;
}
