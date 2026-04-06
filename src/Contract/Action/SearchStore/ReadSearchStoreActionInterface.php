<?php

namespace OneToMany\LlmSdk\Contract\Action\SearchStore;

use OneToMany\LlmSdk\Request\SearchStore\ReadSearchStoreRequest;
use OneToMany\LlmSdk\Response\SearchStore\ReadSearchStoreResponse;

interface ReadSearchStoreActionInterface
{
    public function act(ReadSearchStoreRequest $request): ReadSearchStoreResponse;
}
