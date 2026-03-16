<?php

namespace OneToMany\LlmSdk\Contract\Action\Batch;

use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Response\Batch\ReadBatchResponse;

interface ReadBatchActionInterface
{
    public function act(ReadRequest $request): ReadBatchResponse;
}
