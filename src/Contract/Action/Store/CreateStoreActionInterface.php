<?php

namespace OneToMany\LlmSdk\Contract\Action\Store;

use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;
use OneToMany\LlmSdk\Response\Store\CreateStoreResponse;

interface CreateStoreActionInterface
{
    public function act(CreateSearchStoreRequest $request): CreateStoreResponse;
}
