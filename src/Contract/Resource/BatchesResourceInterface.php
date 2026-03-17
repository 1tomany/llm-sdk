<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadBatchRequest;
use OneToMany\LlmSdk\Response\Batch\CreateBatchResponse;
use OneToMany\LlmSdk\Response\Batch\ReadBatchResponse;

interface BatchesResourceInterface
{
    public function create(CreateRequest $request): CreateBatchResponse;

    public function read(ReadBatchRequest $request): ReadBatchResponse;
}
