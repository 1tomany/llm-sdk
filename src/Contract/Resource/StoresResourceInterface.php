<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\Store\CreateStoreRequest;
use OneToMany\LlmSdk\Response\Store\CreateStoreResponse;

interface StoresResourceInterface
{
    public function create(CreateStoreRequest $request): CreateStoreResponse;
}
