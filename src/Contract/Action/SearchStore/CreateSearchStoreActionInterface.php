<?php

namespace OneToMany\LlmSdk\Contract\Action\SearchStore;

use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;
use OneToMany\LlmSdk\Response\SearchStore\CreateSearchStoreResponse;

interface CreateSearchStoreActionInterface
{
    public function act(CreateSearchStoreRequest $request): CreateSearchStoreResponse;
}
