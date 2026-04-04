<?php

namespace OneToMany\LlmSdk\Contract\Action\Store;

use OneToMany\LlmSdk\Request\Store\CreateStoreRequest;
use OneToMany\LlmSdk\Response\Store\CreateStoreResponse;

interface CreateStoreActionInterface
{
    public function act(CreateStoreRequest $request): CreateStoreResponse;
}
